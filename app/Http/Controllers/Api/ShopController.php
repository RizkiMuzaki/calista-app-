<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\CharacterItem;
use App\Models\ChildItem;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * 🎓 LEARNING: ShopController — API untuk Toko Baju Nusa
 * 
 * Model Hybrid (tanpa koin):
 * - FREE: 2 baju gratis otomatis dimiliki semua anak.
 * - REWARD: Baju unlock otomatis setelah anak capai milestone belajar.
 * - PREMIUM: Baju eksklusif hanya untuk subscriber CALISTA Premium.
 * 
 * Semua endpoint dilindungi Sanctum middleware.
 */
class ShopController extends Controller
{
    /**
     * GET /api/shop
     * Daftar semua baju yang tersedia di toko.
     * 
     * Mengembalikan status setiap baju:
     * - "owned" = Sudah dimiliki (bisa di-equip)
     * - "equipped" = Sedang dipakai
     * - "locked_reward" = Belum unlock (perlu belajar dulu)
     * - "locked_premium" = Perlu langganan premium
     * - "available" = Bisa diambil (free)
     */
    public function items(Request $request)
    {
        try {
            $childId = $request->query('child_id');

            if (!$childId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter child_id wajib diisi',
                ], 422);
            }

            // Pastikan anak milik user yang login
            $child = Anak::where('id', $childId)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $this->ensureFreeItems($child);

            // Cek subscription status user
            $hasSubscription = $request->user()->hasActiveSubscription();

            // Ambil semua item aktif dengan PAGINATION (Phase 8)
            $perPage = $request->query('per_page', 10);
            $paginatedItems = CharacterItem::active()
                ->orderBy('sort_order')
                ->paginate($perPage);

            // Ambil item milik anak ini
            $ownedItemIds = ChildItem::where('anak_id', $childId)
                ->pluck('character_item_id')
                ->toArray();

            $equippedItemId = ChildItem::where('anak_id', $childId)
                ->where('is_equipped', true)
                ->value('character_item_id');

            // Build response dari paginated items
            $shopItems = collect($paginatedItems->items())->map(function ($item) use ($ownedItemIds, $equippedItemId, $hasSubscription) {
                // Determine status
                $status = 'available';
                if (in_array($item->id, $ownedItemIds)) {
                    $status = ($item->id === $equippedItemId) ? 'equipped' : 'owned';
                } elseif ($item->unlock_type === 'reward') {
                    $status = 'locked_reward';
                } elseif ($item->unlock_type === 'star_reward') {
                    $status = 'locked_star';
                } elseif ($item->unlock_type === 'premium') {
                    $status = $hasSubscription ? 'available' : 'locked_premium';
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'image_url' => $item->image_url,
                    'unlock_type' => $item->unlock_type,
                    'reward_condition' => $item->reward_condition,
                    'stars_required' => $item->unlock_type === 'star_reward' ? (int) $item->reward_condition : 0,
                    'status' => $status,
                ];
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Daftar baju berhasil diambil',
                'data'    => [
                    'items'            => $shopItems,
                    'current_page'     => $paginatedItems->currentPage(),
                    'last_page'        => $paginatedItems->lastPage(),
                    'per_page'         => $paginatedItems->perPage(),
                    'total'            => $paginatedItems->total(),
                    'has_more'         => $paginatedItems->hasMorePages(),
                    'has_premium'      => $hasSubscription,
                    'equipped_item_id' => $equippedItemId,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching shop items', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil daftar baju',
            ], 500);
        }
    }

    /**
     * POST /api/shop/claim
     * Claim baju reward setelah anak selesai milestone belajar.
     * 
     * 🎓 LEARNING: Ini dipanggil setelah anak selesai level tertentu.
     * Berbeda dengan "buy" — tidak butuh koin, tapi butuh prestasi!
     */
    public function claimReward(Request $request)
    {
        try {
            $request->validate([
                'child_id' => 'required|integer',
                'item_id' => 'required|integer',
            ]);

            // Pastikan anak milik user login
            $child = Anak::where('id', $request->child_id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            // Cek item exist & tipe reward
            $item = CharacterItem::active()->find($request->item_id);

            if (!$item) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Baju tidak ditemukan',
                ], 404);
            }

            if ($item->unlock_type !== 'reward' && $item->unlock_type !== 'star_reward') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Baju ini bukan tipe reward atau star reward',
                ], 422);
            }

            // Cek sudah dimiliki?
            $alreadyOwned = ChildItem::where('anak_id', $child->id)
                ->where('character_item_id', $item->id)
                ->exists();

            if ($alreadyOwned) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Baju ini sudah kamu miliki!',
                ], 409);
            }

            // ✅ 🎓 LEARNING: Validasi syarat reward (Level minimal / Jumlah bintang)
            if ($item->unlock_type === 'star_reward') {
                $totalStars = (int) $child->progresAnaks()->where('selesai', true)->sum('bintang');
                $requiredStars = (int) $item->reward_condition;

                if ($totalStars < $requiredStars) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Kamu butuh {$requiredStars} bintang untuk unlock baju ini. Sekarang kamu baru punya {$totalStars} bintang.",
                        'current_progress' => $totalStars,
                        'required_progress' => $requiredStars,
                    ], 403);
                }
            } else {
                $completedLevels = $child->progresAnaks()->where('selesai', true)->count();
                $requiredProgress = (int) $item->reward_condition;

                if ($completedLevels < $requiredProgress) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Kamu butuh menyelesaikan {$requiredProgress} level untuk unlock baju ini. Sekarang: {$completedLevels} level.",
                        'current_progress' => $completedLevels,
                        'required_progress' => $requiredProgress,
                    ], 403);
                }
            }

            // Unlock item!
            $childItem = ChildItem::create([
                'anak_id' => $child->id,
                'character_item_id' => $item->id,
                'is_equipped' => false,
                'unlocked_at' => Carbon::now(),
            ]);

            Log::info('Reward outfit claimed', [
                'child_id' => $child->id,
                'item_id' => $item->id,
                'item_name' => $item->name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "🎉 Selamat! Kamu mendapat baju '{$item->name}'!",
                'data' => [
                    'item' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'image_url' => $item->image_url,
                    ],
                    'unlocked_at' => $childItem->unlocked_at->toISOString(),
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error claiming reward', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengklaim baju reward',
            ], 500);
        }
    }

    /**
     * GET /api/shop/inventory
     * Daftar baju yang dimiliki anak (lemari baju).
     */
    public function inventory(Request $request)
    {
        try {
            $childId = $request->query('child_id');

            if (!$childId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Parameter child_id wajib diisi',
                ], 422);
            }

            // Pastikan anak milik user login
            $child = Anak::where('id', $childId)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $inventory = ChildItem::where('anak_id', $childId)
                ->with('characterItem')
                ->orderBy('is_equipped', 'desc') // Yang dipakai di atas
                ->orderBy('unlocked_at', 'desc')
                ->get()
                ->map(function ($ci) {
                    return [
                        'id' => $ci->id,
                        'item_id' => $ci->character_item_id,
                        'name' => $ci->characterItem->name,
                        'description' => $ci->characterItem->description,
                        'image_url' => $ci->characterItem->image_url,
                        'unlock_type' => $ci->characterItem->unlock_type,
                        'is_equipped' => $ci->is_equipped,
                        'unlocked_at' => $ci->unlocked_at?->toISOString(),
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Inventory baju berhasil diambil',
                'data' => [
                    'items' => $inventory,
                    'total' => $inventory->count(),
                    'equipped' => $inventory->firstWhere('is_equipped', true),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching inventory', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil inventory',
            ], 500);
        }
    }

    /**
     * POST /api/shop/equip
     * Pakai baju (ganti outfit Nusa).
     * 
     * 🎓 LEARNING: Hanya satu baju yang bisa dipakai sekaligus.
     * Jadi saat equip baju baru, baju lama otomatis di-unequip.
     */
    public function equip(Request $request)
    {
        try {
            $request->validate([
                'child_id' => 'required|integer',
                'item_id' => 'required|integer',
            ]);

            // Pastikan anak milik user login
            $child = Anak::where('id', $request->child_id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            // Cek anak punya item ini
            $childItem = ChildItem::where('anak_id', $child->id)
                ->where('character_item_id', $request->item_id)
                ->first();

            if (!$childItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kamu belum punya baju ini!',
                ], 404);
            }

            // Unequip semua baju lain dulu
            ChildItem::where('anak_id', $child->id)
                ->where('is_equipped', true)
                ->update(['is_equipped' => false]);

            // Equip baju baru
            $childItem->update(['is_equipped' => true]);

            $item = $childItem->characterItem;

            Log::info('Outfit equipped', [
                'child_id' => $child->id,
                'item_id' => $item->id,
                'item_name' => $item->name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Nusa sekarang pakai '{$item->name}'! 🎉",
                'data' => [
                    'equipped_item' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'image_url' => $item->image_url,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error equipping item', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memakai baju',
            ], 500);
        }
    }

    /**
     * POST /api/shop/buy-premium
     * Beli baju premium (khusus subscriber aktif).
     * 
     * 🎓 LEARNING: Flow Pembelian Baju Premium
     * 1. Flutter cek apakah user subscriber aktif (GET /api/subscription/status)
     * 2. Flutter tampilkan Toko Misterius dengan baju premium
     * 3. User klik "Beli" → Parental Gate Layer 2 (password)
     * 4. Setelah password benar → panggil endpoint ini
     * 5. Backend validasi subscription + unlock baju → masuk inventory anak
     */
    public function buyPremium(Request $request)
    {
        try {
            $request->validate([
                'child_id' => 'required|integer',
                'item_id' => 'required|integer',
            ]);

            // Pastikan anak milik user login
            $child = Anak::where('id', $request->child_id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            // 🔒 Cek subscription aktif — WAJIB!
            $hasSubscription = $request->user()->hasActiveSubscription();

            if (!$hasSubscription) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Fitur ini hanya untuk pengguna premium CALISTA.',
                ], 403);
            }

            // Cek item exist & tipe premium
            $item = CharacterItem::active()->find($request->item_id);

            if (!$item) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Baju tidak ditemukan',
                ], 404);
            }

            if ($item->unlock_type !== 'premium') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Baju ini bukan tipe premium. Gunakan endpoint /claim untuk baju reward.',
                ], 422);
            }

            // Cek sudah dimiliki?
            $alreadyOwned = ChildItem::where('anak_id', $child->id)
                ->where('character_item_id', $item->id)
                ->exists();

            if ($alreadyOwned) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Baju ini sudah kamu miliki!',
                ], 409);
            }

            // ✅ Unlock baju premium!
            $childItem = ChildItem::create([
                'anak_id' => $child->id,
                'character_item_id' => $item->id,
                'is_equipped' => false,
                'unlocked_at' => Carbon::now(),
            ]);

            Log::info('Premium outfit purchased', [
                'user_id' => $request->user()->id,
                'child_id' => $child->id,
                'item_id' => $item->id,
                'item_name' => $item->name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "🎉 Baju '{$item->name}' berhasil dibeli! Cek di Lemari Baju ya!",
                'data' => [
                    'item' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'image_url' => $item->image_url,
                    ],
                    'unlocked_at' => $childItem->unlocked_at->toISOString(),
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error buying premium item', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membeli baju premium',
            ], 500);
        }
    }

    private function ensureFreeItems(Anak $child): void
    {
        $freeItems = CharacterItem::active()->free()->orderBy('sort_order')->get();

        foreach ($freeItems as $index => $item) {
            ChildItem::firstOrCreate(
                [
                    'anak_id' => $child->id,
                    'character_item_id' => $item->id,
                ],
                [
                    'is_equipped' => $index === 0,
                    'unlocked_at' => Carbon::now(),
                ]
            );
        }

        $hasEquippedItem = ChildItem::where('anak_id', $child->id)
            ->where('is_equipped', true)
            ->whereHas('characterItem', function ($query) {
                $query->where('is_active', true);
            })
            ->exists();

        if (!$hasEquippedItem && $freeItems->isNotEmpty()) {
            ChildItem::where('anak_id', $child->id)
                ->where('character_item_id', $freeItems->first()->id)
                ->update(['is_equipped' => true]);
        }
    }
}

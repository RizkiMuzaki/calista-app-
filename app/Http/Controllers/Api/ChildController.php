<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

/**
 * 🎓 LEARNING: API Controller untuk Flutter
 * Controller ini menyediakan endpoint REST API untuk manajemen profil anak.
 * Flutter akan memanggil endpoint ini melalui HTTP request.
 * 
 * Semua endpoint dilindungi oleh Sanctum middleware (harus login dulu).
 */
class ChildController extends Controller
{
    /**
     * GET /api/children
     * Ambil semua anak milik user yang sedang login.
     * 
     * 🎓 LEARNING: $request->user() otomatis dapat user dari token Sanctum.
     * Jadi setiap orang tua hanya bisa lihat anak-anaknya sendiri (aman!).
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $maxChildren = 1; // Default Free
            
            $activeSub = $user->subscriptions()
                ->where('status', 'aktif')
                ->where('tanggal_berakhir', '>', now())
                ->with('plan')
                ->first();
                
            if ($activeSub) {
                if ($activeSub->plan && str_contains($activeSub->plan->nama_paket, 'Mingguan')) {
                    $maxChildren = 2; // Weekly Plan
                } else {
                    $maxChildren = 100; // Monthly / Yearly
                }
            }

            $children = Anak::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function (Anak $child) {
                    // Get equipped outfit
                    $equippedOutfit = $child->childItems()
                        ->where('is_equipped', true)
                        ->with('characterItem')
                        ->first();
                    $equippedOutfitPath = $equippedOutfit ? $equippedOutfit->characterItem->image_url : 'assets/images/shop/nusa_safari_klasik.png';

                    return [
                        'id' => $child->id,
                        'nama_anak' => $child->nama_anak,
                        'tanggal_lahir' => Carbon::parse($child->tanggal_lahir)->format('Y-m-d'),
                        'umur' => $child->tanggal_lahir->age,
                        'jenis_kelamin' => $child->jenis_kelamin,
                        'avatar_path' => $child->avatar_path,
                        'background_path' => $child->background_path,
                        'equipped_outfit' => $equippedOutfitPath,
                        'is_active' => $child->is_active,
                        'timer' => [
                            'limit_detik' => $child->limit_detik,
                            'sisa_detik' => $child->sisa_detik,
                            'formatted' => $child->getFormattedRemainingTime(),
                            'has_time' => $child->hasTimeRemaining(),
                        ],
                        'created_at' => $child->created_at->toISOString(),
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Daftar anak berhasil diambil',
                'data' => $children,
                'total' => $children->count(),
                'max_children' => $maxChildren,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching children', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil daftar anak',
            ], 500);
        }
    }
    /**
     * POST /api/children
     * Tambah profil anak baru.
     * 
     * POST /api/children
     * Tambah profil anak baru.
     * 
     * 🎓 LEARNING: Validasi memastikan data yang masuk benar.
     * 'tanggal_lahir' harus sebelum hari ini (anak harus sudah lahir!).
     */
    public function store(Request $request)
    {
        try {
            // Batasan tambah anak untuk user Free / Premium / Weekly
            $user = $request->user();
            $childCount = Anak::where('user_id', $user->id)->count();
            
            $activeSub = $user->subscriptions()
                ->where('status', 'aktif')
                ->where('tanggal_berakhir', '>', now())
                ->with('plan')
                ->first();
                
            $maxChildren = 1; // Default Free
            if ($activeSub) {
                if ($activeSub->plan && str_contains($activeSub->plan->nama_paket, 'Mingguan')) {
                    $maxChildren = 2; // Weekly
                } else {
                    $maxChildren = 100; // Monthly / Yearly
                }
            }
            
            if ($childCount >= $maxChildren) {
                $planName = $activeSub ? ($activeSub->plan ? $activeSub->plan->nama_paket : 'Calista Plus') : 'Free';
                return response()->json([
                    'status' => 'error',
                    'message' => "Batas maksimal penambahan anak untuk akun {$planName} adalah {$maxChildren} anak. Silakan tingkatkan paket Anda untuk menambah lebih banyak anak.",
                ], 403);
            }

            $validated = $request->validate([
                'nama_anak' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date|before:today',
                'jenis_kelamin' => 'required|in:L,P',
                'avatar_path' => 'nullable|string|max:255',
                'background_path' => 'nullable|string|max:255',
                'limit_detik' => 'nullable|integer|min:0|max:14400',
            ]);

            // Validasi premium avatar untuk user Free
            if (!empty($validated['avatar_path']) && $validated['avatar_path'] !== 'assets/images/avatar/free/free_avatar_1.png') {
                if (!$request->user()->hasActiveSubscription() && !$request->user()->hasEverSubscribed()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pilihan avatar ini hanya untuk pengguna premium CALISTA.',
                    ], 403);
                }
            }

            $childData = [
                'user_id' => $request->user()->id,
                'nama_anak' => $validated['nama_anak'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'limit_detik' => $validated['limit_detik'] ?? 0,
                'sisa_detik' => $validated['limit_detik'] ?? 0,
                'tanggal_reset' => Carbon::today(),
            ];

            if (Schema::hasColumn('anaks', 'avatar_path')) {
                $childData['avatar_path'] = $validated['avatar_path'] ?? null;
            }

            if (Schema::hasColumn('anaks', 'background_path')) {
                $childData['background_path'] = $validated['background_path'] ?? null;
            }

            $child = Anak::create($childData);

            return response()->json([
                'status' => 'success',
                'message' => 'Profil anak berhasil ditambahkan',
                'data' => [
                    'id' => $child->id,
                    'nama_anak' => $child->nama_anak,
                    'tanggal_lahir' => Carbon::parse($child->tanggal_lahir)->format('Y-m-d'),
                    'umur' => $child->tanggal_lahir->age,
                    'jenis_kelamin' => $child->jenis_kelamin,
                    'avatar_path' => $child->avatar_path,
                    'background_path' => $child->background_path,
                    'equipped_outfit' => 'assets/images/shop/nusa_safari_klasik.png',
                    'limit_detik' => $child->limit_detik,
                ],
            ], 201);


        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating child', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan anak',
            ], 500);
        }
    }

    /**
     * GET /api/children/{id}
     * Detail satu anak, termasuk progres dan timer.
     */
    public function show(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            // Reset timer harian jika perlu
            $child->checkAndResetDaily();

            // Get equipped outfit
            $equippedOutfit = $child->childItems()
                ->where('is_equipped', true)
                ->with('characterItem')
                ->first();
            $equippedOutfitPath = $equippedOutfit ? $equippedOutfit->characterItem->image_url : 'assets/images/shop/nusa_safari_klasik.png';

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $child->id,
                    'nama_anak' => $child->nama_anak,
                    'tanggal_lahir' => Carbon::parse($child->tanggal_lahir)->format('Y-m-d'),
                    'umur' => $child->tanggal_lahir->age,
                    'jenis_kelamin' => $child->jenis_kelamin,
                    'avatar_path' => $child->avatar_path,
                    'background_path' => $child->background_path,
                    'equipped_outfit' => $equippedOutfitPath,
                    'is_active' => $child->is_active,
                    'timer' => [
                        'limit_detik' => $child->limit_detik,
                        'sisa_detik' => $child->sisa_detik,
                        'formatted' => $child->getFormattedRemainingTime(),
                        'has_time' => $child->hasTimeRemaining(),
                        'is_running' => $child->timer_started_at !== null,
                    ],
                    'progress' => $child->getAllProgress(),
                    'recent_activity' => $child->getRecentProgress(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching child detail', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail anak',
            ], 500);
        }
    }

    /**
     * PUT /api/children/{id}
     * Update profil anak (nama, tanggal lahir, limit timer).
     */
    public function update(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $validated = $request->validate([
                'nama_anak' => 'sometimes|string|max:255',
                'tanggal_lahir' => 'sometimes|date|before:today',
                'jenis_kelamin' => 'sometimes|in:L,P',
                'avatar_path' => 'sometimes|nullable|string|max:255',
                'background_path' => 'sometimes|nullable|string|max:255',
                'limit_detik' => 'sometimes|integer|min:0|max:14400',
            ]);

            // Validasi premium avatar untuk user Free
            if (array_key_exists('avatar_path', $validated) && !empty($validated['avatar_path']) && $validated['avatar_path'] !== 'assets/images/avatar/free/free_avatar_1.png') {
                if (!$request->user()->hasActiveSubscription() && !$request->user()->hasEverSubscribed()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pilihan avatar ini hanya untuk pengguna premium CALISTA.',
                    ], 403);
                }
            }

            // Validasi perubahan nama anak
            if (array_key_exists('nama_anak', $validated) && $validated['nama_anak'] !== $child->nama_anak) {
                $hasSubscription = $request->user()->hasActiveSubscription();
                if (!$hasSubscription) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Fitur mengubah nama anak hanya tersedia untuk pengguna premium CALISTA.',
                    ], 403);
                }

                if ($child->rename_count >= 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Batas ganti nama anak untuk pelanggan premium telah tercapai.',
                    ], 422);
                }

                // Increment rename count
                $validated['rename_count'] = $child->rename_count + 1;
            }

            if (array_key_exists('limit_detik', $validated)) {
                $validated['sisa_detik'] = $validated['limit_detik'];
                $validated['tanggal_reset'] = Carbon::today();
                $validated['timer_started_at'] = null;
                $validated['timer_last_updated'] = null;
            }

            $child->update($validated);

            Log::info('Child profile updated', [
                'child_id' => $child->id,
                'updated_fields' => array_keys($validated),
            ]);

            // Hapus ZIP lama jika nama anak diubah agar suara Nusa merekam ulang nama baru
            if (array_key_exists('nama_anak', $validated)) {
                $zipPath = "audio_packs/child_{$child->id}.zip";
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($zipPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($zipPath);
                    \Illuminate\Support\Facades\Cache::forget("audio_pack_status_{$child->id}");
                }
            }

            // Get equipped outfit
            $equippedOutfit = $child->childItems()
                ->where('is_equipped', true)
                ->with('characterItem')
                ->first();
            $equippedOutfitPath = $equippedOutfit ? $equippedOutfit->characterItem->image_url : 'assets/images/shop/nusa_safari_klasik.png';

            return response()->json([
                'status' => 'success',
                'message' => 'Profil anak berhasil diperbarui',
                'data' => [
                    'id' => $child->id,
                    'nama_anak' => $child->nama_anak,
                    'tanggal_lahir' => Carbon::parse($child->tanggal_lahir)->format('Y-m-d'),
                    'umur' => $child->tanggal_lahir->age,
                    'jenis_kelamin' => $child->jenis_kelamin,
                    'avatar_path' => $child->avatar_path,
                    'background_path' => $child->background_path,
                    'equipped_outfit' => $equippedOutfitPath,
                    'limit_detik' => $child->limit_detik,
                ],
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating child', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui profil anak',
            ], 500);
        }
    }

    /**
     * DELETE /api/children/{id}
     * Hapus profil anak (soft delete — data progres tetap ada).
     */
    public function destroy(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $nama = $child->nama_anak;
            $child->delete();

            Log::info('Child profile deleted', [
                'child_id' => $id,
                'nama' => $nama,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Profil anak '{$nama}' berhasil dihapus",
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error deleting child', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus profil anak',
            ], 500);
        }
    }

    /**
     * POST /api/children/{id}/timer/start
     * Mulai timer belajar anak.
     * 
     * 🎓 LEARNING: Timer berjalan di server (bukan di HP).
     * Ini lebih aman karena anak tidak bisa "hack" timer dari HP-nya.
     */
    public function startTimer(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            // Reset harian jika perlu
            $child->checkAndResetDaily();

            if (!$child->hasTimeRemaining()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Waktu belajar hari ini sudah habis!',
                    'data' => [
                        'sisa_detik' => 0,
                        'formatted' => '00:00',
                    ],
                ], 403);
            }

            $child->startTimer();

            return response()->json([
                'status' => 'success',
                'message' => 'Timer dimulai!',
                'data' => [
                    'sisa_detik' => $child->sisa_detik,
                    'formatted' => $child->getFormattedRemainingTime(),
                    'timer_started_at' => $child->timer_started_at->toISOString(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error starting timer', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memulai timer',
            ], 500);
        }
    }

    /**
     * POST /api/children/{id}/timer/stop
     * Hentikan timer belajar anak.
     */
    public function stopTimer(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $child->stopTimer();

            return response()->json([
                'status' => 'success',
                'message' => 'Timer dihentikan',
                'data' => [
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error stopping timer', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghentikan timer',
            ], 500);
        }
    }



    /**
     * GET /api/children/{id}/timer
     * Cek status timer anak (sisa waktu, sedang berjalan atau tidak).
     */
    public function timerStatus(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $child->checkAndResetDaily();
            if ($child->timer_started_at) {
                $child->updateRunningTimer();
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'child_id' => $child->id,
                    'nama_anak' => $child->nama_anak,
                    'limit_detik' => $child->limit_detik,
                    'sisa_detik' => $child->sisa_detik,
                    'formatted' => $child->getFormattedRemainingTime(),
                    'has_time' => $child->hasTimeRemaining(),
                    'is_running' => $child->timer_started_at !== null,
                    'should_lock' => $child->limit_detik > 0 && !$child->hasTimeRemaining(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error checking timer status', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengecek status timer',
            ], 500);
        }
    }

    /**
     * POST /api/children/{id}/timer/verify-pin
     * Verifikasi PIN orang tua untuk unlock layar setelah timer habis.
     *
     * 🎓 LEARNING: PIN ini mencoba parent_pin dulu jika diset, jika tidak/tidak cocok coba password.
     *
     * Request body: { "pin": "parent_pin_atau_password" }
     */
    public function verifyPin(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'pin' => 'required|string',
            ]);

            $user = $request->user();
            $child = Anak::where('id', $id)
                        ->where('user_id', $user->id)
                        ->first();

            if (!$child) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $parentVerified = false;
            if (!empty($user->parent_pin)) {
                $parentVerified = \Illuminate\Support\Facades\Hash::check($validated['pin'], $user->parent_pin);
            }
            if (!$parentVerified) {
                $parentVerified = \Illuminate\Support\Facades\Hash::check($validated['pin'], $user->password);
            }

            if (!$parentVerified) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'PIN salah.',
                    'unlocked' => false,
                ], 403);
            }

            return response()->json([
                'status'   => 'success',
                'message'  => 'PIN benar!',
                'unlocked' => true,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error verifying PIN', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal verifikasi PIN',
            ], 500);
        }
    }

    public function generateAudioPack(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $validated = $request->validate([
                'texts' => 'required|array',
                'texts.*.key' => 'required|string',
                'texts.*.text' => 'required|string',
            ]);

            // Cegah duplikasi job jika sedang diproses (kecuali jika sudah stuck lebih dari 10 menit)
            $status = \Illuminate\Support\Facades\Cache::get("audio_pack_status_{$child->id}");
            if ($status && ($status['status'] ?? '') === 'processing') {
                $updatedAt = $status['updated_at'] ?? 0;
                $isStuck = (now()->timestamp - $updatedAt) > 600; // 10 menit
                if (!$isStuck) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Proses pembuatan paket audio Nusa sedang berjalan.',
                        'data' => [
                            'child_id' => $child->id,
                            'status_url' => url("/api/children/{$child->id}/audio-pack/status"),
                        ]
                    ], 202);
                }
            }

            // Cooldown 15 menit untuk regenerasi (jika ZIP dihapus akibat ganti nama anak)
            $cooldownKey = "audio_pack_cooldown_{$child->id}";
            if (\Illuminate\Support\Facades\Cache::has($cooldownKey)) {
                $secondsLeft = \Illuminate\Support\Facades\Cache::get($cooldownKey) - now()->timestamp;
                if ($secondsLeft > 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Batas pembuatan audio tercapai. Silakan coba lagi dalam ' . ceil($secondsLeft / 60) . ' menit.',
                    ], 429);
                }
            }

            // Cek jika ZIP sudah ada di public storage (Bypass Instan jika nama anak tidak berubah)
            $zipPath = "audio_packs/child_{$child->id}.zip";
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($zipPath)) {
                \Illuminate\Support\Facades\Cache::put("audio_pack_status_{$child->id}", [
                    'status' => 'completed',
                    'progress' => 100,
                    'url' => asset("storage/audio_packs/child_{$child->id}.zip"),
                    'updated_at' => now()->toIso8601String(),
                ], now()->addDays(7));

                return response()->json([
                    'status' => 'success',
                    'message' => 'Paket audio Nusa siap diunduh.',
                    'data' => [
                        'child_id' => $child->id,
                        'status_url' => url("/api/children/{$child->id}/audio-pack/status"),
                    ]
                ], 202);
            }

            // Set cooldown 15 menit
            \Illuminate\Support\Facades\Cache::put($cooldownKey, now()->addMinutes(15)->timestamp, now()->addMinutes(15));

            // Dispatch job asinkron untuk membuat paket suara anak
            \App\Jobs\GenerateChildTtsPack::dispatch($child, $validated['texts']);

            return response()->json([
                'status' => 'success',
                'message' => 'Proses pembuatan paket audio Nusa telah dimulai di latar belakang.',
                'data' => [
                    'child_id' => $child->id,
                    'status_url' => url("/api/children/{$child->id}/audio-pack/status"),
                ]
            ], 202);

        } catch (\Exception $e) {
            Log::error('Error generating audio pack', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memulai pembuatan paket audio: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function audioPackStatus(Request $request, $id)
    {
        try {
            $child = Anak::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan',
                ], 404);
            }

            $status = \Illuminate\Support\Facades\Cache::get("audio_pack_status_{$child->id}", [
                'status' => 'not_started',
                'progress' => 0,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $status
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error checking audio pack status', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memeriksa status paket audio: ' . $e->getMessage(),
            ], 500);
        }
    }
}


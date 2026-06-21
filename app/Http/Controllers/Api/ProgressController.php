<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\ProgresAnak;
use App\Models\Level;
use App\Models\Module;
use App\Models\Mood;
use App\Models\PlaySession;
use App\Models\CharacterItem;
use App\Models\ChildItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
/**
 * 🎓 LEARNING: ProgressController — API untuk Progres Belajar Anak
 *
 * Dua fungsi utama:
 * 1. store()  — Simpan hasil belajar setelah anak selesai 1 level.
 * 2. report() — Laporan lengkap progres anak (untuk Parent Center).
 *
 * Data progres sangat penting karena:
 * - Menentukan apakah anak bisa unlock baju reward.
 * - Jadi bahan laporan gaya belajar (V-A-K) di Parent Center.
 * - Motivasi anak lewat bintang & skor.
 */
class ProgressController extends Controller
{
    /**
     * POST /api/progress
     * Simpan atau update hasil belajar anak pada satu level.
     *
     * 🎓 LEARNING: updateOrCreate() dipakai karena tabel progres_anaks
     * punya constraint UNIQUE(anak_id, level_id). Jadi kalau anak
     * mengulang level yang sama, skornya di-update, bukan insert baru.
     *
     * Request body:
     * {
     *   "anak_id": 1,
     *   "level_id": 3,
     *   "score": 85,
     *   "bintang": 4,     // 1-5 bintang
     *   "selesai": true
     * }
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'anak_id'  => 'required|exists:anaks,id',
                'level_id' => 'nullable|exists:levels,id',
                'local_level_id' => 'nullable|string|max:80',
                'module_slug' => 'nullable|string|max:80',
                'level_number' => 'nullable|integer|min:1|max:100',
                'level_title' => 'nullable|string|max:255',
                'score'    => 'required|integer|min:0|max:100',
                'current_score' => 'nullable|integer|min:0|max:100',
                'bintang'  => 'required|integer|min:0|max:5',
                'selesai'  => 'required|boolean',
                'current_item' => 'nullable|integer|min:0',
                'total_items' => 'nullable|integer|min:0',
                'mistakes' => 'nullable|integer|min:0',
                'lives_remaining' => 'nullable|integer|min:0|max:3',
                'duration_seconds' => 'nullable|integer|min:0',
                'session_id' => 'nullable|string|max:120',
                'metadata' => 'nullable|array',
            ]);

            // 🎓 Pastikan anak ini milik user yang login (keamanan!)
            $user = $request->user();
            $anak = Anak::where('id', $validated['anak_id'])
                        ->where('user_id', $user->id)
                        ->first();

            if (!$anak) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anak tidak ditemukan atau bukan milik Anda',
                ], 403);
            }

            // 🎓 updateOrCreate: Insert jika belum ada, update jika sudah.
            // Cocok untuk progres karena 1 anak hanya punya 1 record per level.
            $levelId = $validated['level_id'] ?? null;
            if (!$levelId) {
                $level = $this->resolveLevelFromClientPayload($validated);
                if (!$level) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Level tidak ditemukan untuk progres ini',
                    ], 422);
                }
                $levelId = $level->id;
                $validated['level_id'] = $levelId;
            }

            $progress = ProgresAnak::firstOrNew([
                'anak_id'  => $validated['anak_id'],
                'level_id' => $levelId,
            ]);

            $progress->current_score = (int) ($validated['current_score'] ?? $validated['score']);
            if ($validated['selesai']) {
                $progress->score = max((int) $progress->score, (int) $validated['score']);
                $progress->bintang = max((int) $progress->bintang, (int) $validated['bintang']);
            }
            $progress->selesai = (bool) $progress->selesai || (bool) $validated['selesai'];
            $progress->current_item = max((int) $progress->current_item, (int) ($validated['current_item'] ?? 0));
            $progress->total_items = max((int) $progress->total_items, (int) ($validated['total_items'] ?? 0));
            $progress->mistakes = max((int) $progress->mistakes, (int) ($validated['mistakes'] ?? 0));
            $progress->lives_remaining = (int) ($validated['lives_remaining'] ?? $progress->lives_remaining ?? 3);
            $progress->duration_seconds = max((int) $progress->duration_seconds, (int) ($validated['duration_seconds'] ?? 0));
            $progress->last_played_at = now();
            $progress->metadata = array_filter([
                ...((array) ($progress->metadata ?? [])),
                ...((array) ($validated['metadata'] ?? [])),
            ], fn ($value) => $value !== null);
            $progress->save();

            // Load relasi untuk response
            $progress->load('level.module');

            $playSession = $this->recordPlaySession($anak, $progress, $validated);

            // ========== REWARD AUTO-UNLOCK (Phase 6.1) ==========
            // 🎓 LEARNING: Setiap kali progress disimpan dan selesai,
            // cek apakah anak sudah capai milestone untuk unlock baju.
            $unlockedRewards = [];
            if ($validated['selesai']) {
                $unlockedRewards = $this->checkAndUnlockRewards($anak, $progress);
            }

            Log::info('Progress saved via API', [
                'anak_id'  => $anak->id,
                'level_id' => $validated['level_id'],
                'score'    => $validated['score'],
                'current_score' => $progress->current_score,
                'current_item' => $progress->current_item,
                'total_items' => $progress->total_items,
                'selesai' => $progress->selesai,
                'rewards_unlocked' => count($unlockedRewards),
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => $unlockedRewards
                    ? 'Progres disimpan & baju baru ter-unlock! 🎉'
                    : 'Progres berhasil disimpan',
                'data'    => [
                    'progress' => $progress,
                    'anak'     => [
                        'id'   => $anak->id,
                        'nama' => $anak->nama_anak,
                    ],
                    'unlocked_rewards' => $unlockedRewards,
                    'play_session' => $playSession ? [
                        'id' => $playSession->id,
                        'session_id' => $playSession->session_uuid,
                        'status' => $playSession->status,
                    ] : null,
                ],
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Progress store error', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan progres',
            ], 500);
        }
    }

    private function resolveLevelFromClientPayload(array $validated): ?Level
    {
        $localLevelId = $validated['local_level_id'] ?? null;
        if ($localLevelId) {
            $level = Level::where('local_level_id', $localLevelId)->first();
            if ($level) {
                return $level;
            }
        }

        // Fallback pencarian manual jika local_level_id kosong
        $slug = $validated['module_slug'] ?? null;
        $levelNumber = $validated['level_number'] ?? null;
        if (!$slug || !$levelNumber) {
            return null;
        }

        $activityType = [
            'membaca' => 'reading',
            'menulis' => 'writing',
            'berhitung' => 'counting',
            'puzzle' => 'puzzle',
            'reading' => 'reading',
            'writing' => 'writing',
            'counting' => 'counting',
        ][$slug] ?? $slug;

        // Cari di chapter default (Zoo / ID 1)
        return Level::where('module_id', 1)
            ->where('activity_type', $activityType)
            ->where('order_number', (int) $levelNumber)
            ->first();
    }

    private function recordPlaySession(Anak $anak, ProgresAnak $progress, array $validated): ?PlaySession
    {
        $sessionId = $validated['session_id'] ?? ($validated['metadata']['session_id'] ?? null);
        if (!$sessionId) {
            return null;
        }

        try {
            $level = $progress->level;
            $module = $level?->module;
            $duration = (int) ($validated['duration_seconds'] ?? 0);
            $endedAt = now();
            $startedAt = $duration > 0 ? $endedAt->copy()->subSeconds($duration) : $endedAt->copy();
            $completed = (bool) ($validated['selesai'] ?? false);
            $metadata = array_filter([
                ...((array) ($validated['metadata'] ?? [])),
                'local_level_id' => $validated['local_level_id'] ?? null,
                'level_number' => $validated['level_number'] ?? null,
            ], fn ($value) => $value !== null);

            $session = PlaySession::where('session_uuid', $sessionId)->first();
            if ($session && (int) $session->anak_id !== (int) $anak->id) {
                Log::warning('Rejected play session UUID reuse across children', [
                    'session_id' => $sessionId,
                    'existing_anak_id' => $session->anak_id,
                    'incoming_anak_id' => $anak->id,
                ]);
                return null;
            }

            $session ??= new PlaySession(['session_uuid' => $sessionId]);
            $finalCompleted = $completed || $session->status === 'completed';
            $finalDuration = max((int) $session->duration_seconds, $duration);
            $finalStartedAt = $finalDuration > 0
                ? $endedAt->copy()->subSeconds($finalDuration)
                : $startedAt;
            $session->fill([
                'user_id' => $anak->user_id,
                'anak_id' => $anak->id,
                'level_id' => $progress->level_id,
                'module_id' => $module?->id,
                'source' => $metadata['source'] ?? 'mobile_game',
                'module_slug' => $module?->slug ?? ($validated['module_slug'] ?? null),
                'module_name' => $module?->name,
                'level_title' => $level?->title ?? ($validated['level_title'] ?? null),
                'status' => $finalCompleted ? 'completed' : 'partial',
                'score' => $finalCompleted
                    ? max((int) $session->score, (int) ($validated['score'] ?? 0))
                    : 0,
                'current_score' => max(
                    (int) $session->current_score,
                    (int) ($validated['current_score'] ?? $validated['score'] ?? 0)
                ),
                'bintang' => $finalCompleted
                    ? max((int) $session->bintang, (int) ($validated['bintang'] ?? 0))
                    : 0,
                'current_item' => max((int) $session->current_item, (int) ($validated['current_item'] ?? 0)),
                'total_items' => max((int) $session->total_items, (int) ($validated['total_items'] ?? 0)),
                'mistakes' => max((int) $session->mistakes, (int) ($validated['mistakes'] ?? 0)),
                'lives_remaining' => (int) ($validated['lives_remaining'] ?? 0),
                'duration_seconds' => $finalDuration,
                'started_at' => $finalStartedAt,
                'ended_at' => $endedAt,
                'played_on' => $endedAt->toDateString(),
                'metadata' => $metadata,
            ]);
            $session->save();

            return $session;
        } catch (\Exception $e) {
            Log::error('Play session record error', [
                'anak_id' => $anak->id,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * GET /api/progress/{child_id}
     * Laporan lengkap progres anak (untuk Parent Center di Flutter).
     *
     * Response JSON berisi:
     * - Ringkasan: total sesi, total waktu, rata-rata skor
     * - Progres per modul (Membaca, Menulis, Berhitung)
     * - Modul terkuat & terlemah
     * - Gaya belajar (V-A-K) — rule-based
     * Laporan lengkap progres anak (untuk Parent Center di Flutter).
     */
    public function report(Request $request, $childId)
    {
        try {
            $user = $request->user();
            /** @var Anak $anak */
            $anak = Anak::where('id', $childId)
                        ->where('user_id', $user->id)
                        ->first();

            if (!$anak) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anak tidak ditemukan atau bukan milik Anda',
                ], 403);
            }

            // 1. Ambil Statistik per Modul & Summary
            $moduleData = $this->_getModuleStats($anak->id);
            $moduleStats = $moduleData['stats'];

            // 2. Strongest & Weakest only make sense after real completed play data exists.
            $playedModules = collect($moduleStats)->where('completed', '>', 0);
            $strongest = $playedModules->sortByDesc('avg_score')->first();
            $weakest = $playedModules->sortBy('avg_score')->first();

            // 3. Gaya Belajar (V-A-K)
            $learningStyle = $this->_calculateLearningStyle($moduleStats);

            // 4. Rekomendasi
            $recommendations = $this->generateRecommendations($moduleStats, $learningStyle);

            // 5. Aktivitas Terbaru
            $recentActivity = $this->_getRecentActivity($anak->id);

            // ========== FINAL RESPONSE ==========
            return response()->json([
                'status'  => 'success',
                'message' => 'Laporan progres anak',
                'data'    => [
                    'child' => [
                        'id'   => $anak->id,
                        'nama' => $anak->nama_anak,
                        'umur' => $anak->tanggal_lahir ? $anak->tanggal_lahir->age : null,
                    ],
                    'summary' => [
                        'total_sessions'     => $moduleData['count_score'],
                        'total_completed'    => $moduleData['total_completed'],
                        'total_levels'       => $moduleData['total_levels'],
                        'overall_percentage' => $moduleData['total_levels'] > 0
                            ? round(($moduleData['total_completed'] / $moduleData['total_levels']) * 100, 1)
                            : 0,
                        'avg_score'          => $moduleData['count_score'] > 0
                            ? round($moduleData['sum_score'] / $moduleData['count_score'], 1)
                            : 0,
                    ],
                    'strongest_module' => $strongest['module_name'] ?? null,
                    'weakest_module'   => $weakest['module_name'] ?? null,
                    'modules'          => $moduleStats,
                    'learning_style'   => $learningStyle,
                    'recommendations'  => $recommendations,
                    'recent_activity'  => $recentActivity,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Progress report error', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengambil laporan progres',
            ], 500);
        }
    }

    /**
     * Helper: Hitung statistik per modul
     */
    private function _getModuleStats($anakId): array
    {
        $activities = [
            [
                'id' => 1,
                'type' => 'counting',
                'slug' => 'berhitung',
                'name' => 'Berhitung',
            ],
            [
                'id' => 2,
                'type' => 'reading',
                'slug' => 'membaca',
                'name' => 'Membaca',
            ],
            [
                'id' => 3,
                'type' => 'writing',
                'slug' => 'menulis',
                'name' => 'Menulis',
            ],
            [
                'id' => 4,
                'type' => 'puzzle',
                'slug' => 'puzzle',
                'name' => 'Puzzle',
            ],
        ];

        $moduleStats = [];
        $totalCompleted = 0;
        $totalLevels = 0;
        $sumScore = 0;
        $countScore = 0;

        foreach ($activities as $act) {
            $levels = Level::where('activity_type', $act['type'])->get();
            $levelIds = $levels->pluck('id');
            $totalInModule = $levelIds->count();

            $progressInModule = ProgresAnak::where('anak_id', $anakId)
                ->whereIn('level_id', $levelIds)
                ->get();

            $completedProgressInModule = $progressInModule->where('selesai', true);

            $completedInModule = $completedProgressInModule->count();
            $avgScoreModule = $completedInModule > 0 ? round($completedProgressInModule->avg('score'), 1) : 0;
            $avgStars = $completedInModule > 0 ? round($completedProgressInModule->avg('bintang'), 1) : 0;
            $earnedStars = $completedInModule > 0 ? (int) $completedProgressInModule->sum('bintang') : 0;

            $moduleStats[] = [
                'module_id'    => $act['id'],
                'module_name'  => $act['name'],
                'module_type'  => $act['type'],
                'module_slug'  => $act['slug'],
                'completed'    => $completedInModule,
                'total_levels' => $totalInModule,
                'percentage'   => $totalInModule > 0 ? round(($completedInModule / $totalInModule) * 100, 1) : 0,
                'avg_score'    => $avgScoreModule,
                'avg_stars'    => $avgStars,
                'earned_stars' => $earnedStars,
            ];
            $totalCompleted += $completedInModule;
            $totalLevels += $totalInModule;
            $sumScore += $completedProgressInModule->sum('score');
            $countScore += $completedInModule;
        }

        return [
            'stats'           => $moduleStats,
            'total_completed' => $totalCompleted,
            'total_levels'    => $totalLevels,
            'sum_score'       => $sumScore,
            'count_score'     => $countScore,
        ];
    }
    private function _calculateLearningStyle(array $moduleStats): array
    {
        $v = 0; $a = 0; $k = 0;
        
        $sumV = 0; $sumA = 0; $sumK = 0;

        foreach ($moduleStats as $ms) {
            $score = $ms['avg_score'] ?? 0;
            $completed = $ms['completed'] ?? 0;
            
            // Only count if there's actual activity
            if ($score > 0 && $completed > 0) {
                $type = $ms['module_slug'] ?? $ms['module_type'] ?? '';

                // 🧠 Advanced Weighting Algorithm (V-A-K Mapping)
                switch ($type) {
                    case 'reading': // Membaca: Dominan Visual & Auditori
                    case 'membaca':
                        $sumV += $score * 0.7;
                        $sumA += $score * 0.3;
                        break;
                    case 'writing': // Menulis: Dominan Kinestetik & Visual
                    case 'menulis':
                        $sumK += $score * 0.8;
                        $sumV += $score * 0.2;
                        break;
                    case 'counting': // Berhitung: Dominan Visual & Kinestetik
                    case 'berhitung':
                        $sumV += $score * 0.6;
                        $sumK += $score * 0.4;
                        break;
                    case 'puzzle':
                        $sumV += $score * 0.5;
                        $sumK += $score * 0.5;
                        break;
                }
            }
        }

        $total = $sumV + $sumA + $sumK;
        $hasData = $total > 0;
        if ($hasData) {
            $v = (int)round(($sumV / $total) * 100);
            $a = (int)round(($sumA / $total) * 100);
            $k = 100 - ($v + $a); // Ensure total is 100%
        }

        return [
            'visual'      => $v,
            'auditory'    => $a,
            'kinesthetic' => $k,
            'has_data'     => $hasData,
            'method'       => 'rule_based_progress_mapping',
        ];
    }

    /**
     * Helper: Ambil log aktivitas terbaru
     */
    private function _getRecentActivity($anakId)
    {
        return ProgresAnak::where('anak_id', $anakId)
            ->with(['level', 'level.module'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function (ProgresAnak $p) {
                return [
                    'level'      => $p->level?->title ?? 'Unknown',
                    'module'     => $p->level?->module?->name ?? 'Unknown',
                    'score'      => $p->score,
                    'current_score' => $p->current_score,
                    'bintang'    => $p->bintang,
                    'selesai'    => $p->selesai,
                    'current_item' => $p->current_item,
                    'total_items' => $p->total_items,
                    'mistakes' => $p->mistakes,
                    'duration_seconds' => $p->duration_seconds,
                    'updated_at' => $p->updated_at->toDateTimeString(),
                ];
            });
    }

    /**
     * Generate rekomendasi berdasarkan data progres & gaya belajar.
     *
     * 🎓 LEARNING: Rule-based recommendation system.
     * Cocok untuk MVP — nanti bisa di-upgrade pakai AI/ML.
     */
    private function generateRecommendations(array $moduleStats, array $learningStyle): array
    {
        $recommendations = [];
        $completedTotal = collect($moduleStats)->sum('completed');

        if ($completedTotal <= 0) {
            return ['Belum ada data bermain. Laporan progress, bintang, dan gaya belajar akan terisi setelah anak menyelesaikan level.'];
        }

        // Cek modul terlemah
        $weakest = collect($moduleStats)->sortBy('avg_score')->first();
        if ($weakest && $weakest['avg_score'] < 60 && $weakest['completed'] > 0) {
            $recommendations[] = "Latihan {$weakest['module_name']} perlu ditingkatkan. Coba ulangi level yang skornya rendah.";
        }

        // Cek modul yang belum dimulai
        foreach ($moduleStats as $ms) {
            if ($ms['completed'] === 0 && $ms['total_levels'] > 0) {
                $recommendations[] = "Anak belum memulai modul {$ms['module_name']}. Yuk coba!";
            }
        }

        // Rekomendasi berdasarkan gaya belajar
        if ($learningStyle['kinesthetic'] < 20) {
            $recommendations[] = "Latih motorik halus dengan aktivitas menulis dan menggambar.";
        }

        if ($learningStyle['visual'] > 50) {
            $recommendations[] = "Anak cenderung visual learner. Bacakan cerita bergambar untuk perkuat literasi.";
        }

        if ($learningStyle['auditory'] > 50) {
            $recommendations[] = "Anak suka belajar lewat suara. Ajak bermain counting games dengan nyanyian.";
        }

        // Default recommendation
        if (empty($recommendations)) {
            $recommendations[] = "Progress anak bagus! Teruskan belajar secara rutin ya 🌟";
        }

        return $recommendations;
    }

    /**
     * Cek milestone dan unlock reward baju secara otomatis.
     *
     * 🎓 LEARNING: Reward System (Phase 6.1)
     * Milestone 1: Selesaikan Level 5 dari modul APAPUN → unlock 1 baju reward
     * Milestone 2: Selesaikan SEMUA level dari 1 modul → unlock 1 baju reward
     *
     * Reward yang sudah di-owned tidak akan di-unlock ulang (idempotent).
     */
    private function checkAndUnlockRewards(Anak $anak, ProgresAnak $progress): array
    {
        $unlockedRewards = [];

        try {
            // Ambil modul dari level yang baru diselesaikan
            $level = $progress->level;
            if (!$level) return [];

            $module = $level->module;
            if (!$module) return [];

            // Hitung berapa level yang sudah selesai di modul ini
            $levelIds = $module->levels->pluck('id');
            $completedCount = ProgresAnak::where('anak_id', $anak->id)
                ->whereIn('level_id', $levelIds)
                ->where('selesai', true)
                ->count();

            $totalLevels = $levelIds->count();

            // 🎓 MILESTONE 1: Selesaikan Level 5 (atau level tertinggi)
            // Cek apakah level yang baru diselesaikan adalah level ke-5+
            if ($level->order_number >= 5) {
                $reward = $this->unlockNextRewardItem($anak);
                if ($reward) {
                    $unlockedRewards[] = $reward;
                    Log::info('Milestone 1: Level 5 completed, reward unlocked', [
                        'anak_id' => $anak->id,
                        'item'    => $reward['name'],
                    ]);
                }
            }

            // 🎓 MILESTONE 2: Selesaikan SEMUA level dalam 1 modul
            if ($completedCount >= $totalLevels && $totalLevels > 0) {
                $reward = $this->unlockNextRewardItem($anak);
                if ($reward) {
                    $unlockedRewards[] = $reward;
                    Log::info('Milestone 2: All levels completed in module, reward unlocked', [
                        'anak_id' => $anak->id,
                        'module'  => $module->name,
                        'item'    => $reward['name'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Reward unlock error', ['error' => $e->getMessage()]);
            // Jangan gagalkan save progress hanya karena reward error
        }

        return $unlockedRewards;
    }

    /**
     * Unlock baju reward berikutnya yang belum dimiliki anak.
     *
     * 🎓 LEARNING: Method ini idempotent — kalau semua reward sudah
     * di-unlock, return null tanpa error.
     */
    private function unlockNextRewardItem(Anak $anak): ?array
    {
        // Ambil ID baju reward yang SUDAH dimiliki anak
        $ownedItemIds = ChildItem::where('anak_id', $anak->id)
            ->pluck('character_item_id')
            ->toArray();

        // Cari baju reward yang BELUM dimiliki
        $nextReward = CharacterItem::reward()
            ->where('is_active', true)
            ->whereNotIn('id', $ownedItemIds)
            ->orderBy('sort_order')
            ->first();

        if (!$nextReward) {
            return null; // Semua reward sudah ter-unlock
        }

        // Auto-assign ke anak
        ChildItem::create([
            'anak_id'           => $anak->id,
            'character_item_id' => $nextReward->id,
            'is_equipped'       => false,
        ]);

        return [
            'id'    => $nextReward->id,
            'name'  => $nextReward->name,
            'image' => $nextReward->image_url,
            'type'  => 'reward',
        ];
    }

    /**
     * GET /api/progress/{child_id}/sessions
     * Ringkasan sesi bermain mingguan/bulanan untuk dashboard orang tua.
     */
    public function sessionReport(Request $request, $childId)
    {
        try {
            $anak = $this->findOwnedChild($request, $childId);
            if (!$anak) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan atau bukan milik Anda',
                ], 403);
            }

            [$start, $end, $period] = $this->resolveSessionPeriod($request);
            $sessions = $this->sessionQuery($anak->id, $start, $end)
                ->orderBy('ended_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan sesi bermain anak',
                'data' => $this->buildSessionReportPayload($anak, $sessions, $start, $end, $period),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Session report error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil laporan sesi',
            ], 500);
        }
    }

    /**
     * GET /api/progress/{child_id}/sessions/export?period=week|month
     * Export CSV ringkas yang bisa dipakai untuk fitur download dashboard.
     */
    public function exportSessions(Request $request, $childId)
    {
        try {
            $anak = $this->findOwnedChild($request, $childId);
            if (!$anak) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anak tidak ditemukan atau bukan milik Anda',
                ], 403);
            }

            [$start, $end, $period] = $this->resolveSessionPeriod($request);
            $sessions = $this->sessionQuery($anak->id, $start, $end)
                ->orderBy('ended_at')
                ->get();

            $filename = sprintf(
                'calista-%s-%s-%s.csv',
                strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $anak->nama_anak)),
                $period,
                $start->format('Ymd') . '-' . $end->format('Ymd')
            );

            $rows = [[
                'Tanggal',
                'Mulai',
                'Selesai',
                'Modul',
                'Level',
                'Status',
                'Durasi Menit',
                'Skor',
                'Bintang',
                'Progress Item',
                'Kesalahan',
            ]];

            foreach ($sessions as $session) {
                $rows[] = [
                    $session->played_on?->toDateString() ?? '',
                    $session->started_at?->format('H:i:s') ?? '',
                    $session->ended_at?->format('H:i:s') ?? '',
                    $session->module_name ?? '-',
                    $session->level_title ?? '-',
                    $session->status,
                    round($session->duration_seconds / 60, 1),
                    $session->score,
                    $session->bintang,
                    "{$session->current_item}/{$session->total_items}",
                    $session->mistakes,
                ];
            }

            // UTF-8 BOM prepended so Excel on Windows renders Indonesian
            // characters (names, etc.) correctly without mojibake.
            $bom = "\xEF\xBB\xBF";
            $csv = $bom . collect($rows)->map(function ($row) {
                return collect($row)->map(function ($value) {
                    $value = (string) $value;
                    return '"' . str_replace('"', '""', $value) . '"';
                })->implode(',');
            })->implode("\n");

            return response($csv, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        } catch (\Exception $e) {
            Log::error('Session export error', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat export laporan sesi',
            ], 500);
        }
    }

    private function findOwnedChild(Request $request, $childId): ?Anak
    {
        return Anak::where('id', $childId)
            ->where('user_id', $request->user()->id)
            ->first();
    }

    private function resolveSessionPeriod(Request $request): array
    {
        $period = $request->query('period', 'week');
        if (!in_array($period, ['week', 'month'], true)) {
            $period = 'week';
        }

        $date = $request->query('date')
            ? Carbon::parse($request->query('date'))
            : now();

        if ($period === 'month') {
            return [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
                'month',
            ];
        }

        return [
            $date->copy()->startOfWeek(Carbon::MONDAY),
            $date->copy()->endOfWeek(Carbon::SUNDAY),
            'week',
        ];
    }

    private function sessionQuery($anakId, Carbon $start, Carbon $end)
    {
        return PlaySession::where('anak_id', $anakId)
            ->whereBetween('played_on', [$start->toDateString(), $end->toDateString()])
            ->with(['level.module', 'module']);
    }

    private function buildSessionReportPayload(Anak $anak, $sessions, Carbon $start, Carbon $end, string $period): array
    {
        $completedSessions = $sessions->where('status', 'completed');
        $scoreSessions = $completedSessions->where('score', '>', 0);
        $moduleBreakdown = $this->buildModuleSessionBreakdown($sessions);
        $moods = Mood::where('anak_id', $anak->id)
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->get();

        return [
            'child' => [
                'id' => $anak->id,
                'nama' => $anak->nama_anak,
                'umur' => $anak->tanggal_lahir ? $anak->tanggal_lahir->age : null,
            ],
            'period' => [
                'type' => $period,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
            ],
            'summary' => [
                'total_sessions' => $sessions->count(),
                'completed_sessions' => $completedSessions->count(),
                'partial_sessions' => $sessions->where('status', '!=', 'completed')->count(),
                'total_play_seconds' => (int) $sessions->sum('duration_seconds'),
                'total_play_minutes' => round($sessions->sum('duration_seconds') / 60, 1),
                'avg_score' => $scoreSessions->count() > 0 ? round($scoreSessions->avg('score'), 1) : 0,
                'earned_stars' => (int) $completedSessions->sum('bintang'),
                'completion_rate' => $sessions->count() > 0
                    ? round(($completedSessions->count() / $sessions->count()) * 100, 1)
                    : 0,
            ],
            'modules' => $moduleBreakdown,
            'learning_style' => $this->_calculateLearningStyle($moduleBreakdown->map(function ($module) {
                return [
                    'module_slug' => $module['module_slug'],
                    'avg_score' => $module['avg_score'],
                    'completed' => $module['completed_sessions'],
                ];
            })->values()->all()),
            'daily' => $this->buildDailySessionBreakdown($sessions, $start, $end),
            'moods' => [
                'summary' => $this->normalizeMoodSummary($moods->groupBy('mood_type')->map->count()),
                'items' => $moods->sortByDesc('created_at')->values()->map(fn ($mood) => [
                    'mood_type' => $mood->mood_type,
                    'date' => $mood->created_at->toDateString(),
                    'time' => $mood->created_at->format('H:i'),
                ]),
            ],
            'recent_sessions' => $sessions->take(10)->values()->map(fn ($session) => $this->sessionResource($session)),
            'export' => [
                'csv_url' => "/api/progress/{$anak->id}/sessions/export?period={$period}&date={$start->toDateString()}",
            ],
        ];
    }

    private function buildModuleSessionBreakdown($sessions)
    {
        return $sessions->groupBy(fn ($session) => $session->module_slug ?? 'unknown')
            ->map(function ($items, $slug) {
                $completed = $items->where('status', 'completed');
                $scoreItems = $completed->where('score', '>', 0);
                return [
                    'module_slug' => $slug,
                    'module_name' => $items->first()->module_name ?? ucfirst($slug),
                    'total_sessions' => $items->count(),
                    'completed_sessions' => $completed->count(),
                    // 'completed' is the canonical key read by _calculateLearningStyle.
                    'completed' => $completed->count(),
                    'total_play_seconds' => (int) $items->sum('duration_seconds'),
                    'total_play_minutes' => round($items->sum('duration_seconds') / 60, 1),
                    'avg_score' => $scoreItems->count() > 0 ? round($scoreItems->avg('score'), 1) : 0,
                    'earned_stars' => (int) $completed->sum('bintang'),
                ];
            })
            ->values();
    }

    private function buildDailySessionBreakdown($sessions, Carbon $start, Carbon $end)
    {
        $days = [];
        $cursor = $start->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $items = $sessions->filter(fn ($session) => $session->played_on?->toDateString() === $key);
            $days[] = [
                'date' => $key,
                'sessions' => $items->count(),
                'completed_sessions' => $items->where('status', 'completed')->count(),
                'play_seconds' => (int) $items->sum('duration_seconds'),
                'play_minutes' => round($items->sum('duration_seconds') / 60, 1),
            ];
            $cursor->addDay();
        }

        return $days;
    }

    private function normalizeMoodSummary($summary)
    {
        $keys = ['senang', 'ceria', 'takut', 'sedih', 'marah'];
        return collect($keys)->mapWithKeys(fn ($key) => [$key => (int) ($summary[$key] ?? 0)]);
    }

    private function sessionResource(PlaySession $session): array
    {
        return [
            'id' => $session->id,
            'session_id' => $session->session_uuid,
            'module_slug' => $session->module_slug,
            'module_name' => $session->module_name,
            'level_title' => $session->level_title,
            'status' => $session->status,
            'score' => $session->score,
            'current_score' => $session->current_score,
            'bintang' => $session->bintang,
            'current_item' => $session->current_item,
            'total_items' => $session->total_items,
            'mistakes' => $session->mistakes,
            'duration_seconds' => $session->duration_seconds,
            'played_on' => $session->played_on?->toDateString(),
            'started_at' => $session->started_at?->toISOString(),
            'ended_at' => $session->ended_at?->toISOString(),
        ];
    }

    /**
     * GET /api/progress/{child_id}/history
     * Riwayat lengkap aktivitas anak (paginated).
     */
    public function history(Request $request, $childId)
    {
        try {
            $user = $request->user();
            $anak = Anak::where('id', $childId)
                        ->where('user_id', $user->id)
                        ->first();

            if (!$anak) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anak tidak ditemukan atau bukan milik Anda',
                ], 403);
            }

            $perPage = $request->get('per_page', 15);
            $history = ProgresAnak::where('anak_id', $childId)
                ->with(['level.module'])
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'status'  => 'success',
                'data'    => [
                    'items' => $history->getCollection()->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'module_name' => $p->level?->module?->name ?? 'Unknown',
                            'level_title' => $p->level?->title ?? 'Level Unknown',
                            'score' => $p->score,
                            'current_score' => $p->current_score,
                            'bintang' => $p->bintang,
                            'selesai' => $p->selesai,
                            'current_item' => $p->current_item,
                            'total_items' => $p->total_items,
                            'mistakes' => $p->mistakes,
                            'duration_seconds' => $p->duration_seconds,
                            'time_ago' => $p->updated_at->diffForHumans(),
                        ];
                    }),
                    'current_page' => $history->currentPage(),
                    'has_more' => $history->hasMorePages(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('History fetch error', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengambil riwayat',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/progress/{child_id}/report-pdf?period=week|month&date=YYYY-MM-DD
     * Generate laporan belajar anak dalam format PDF — ceria, berwarna, mudah dibaca.
     */
    public function exportPdf(Request $request, $childId)
    {
        try {
            $user = $request->user();
            if (!$user->hasActiveSubscription()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Ekspor laporan PDF adalah fitur premium CALISTA. Silakan berlangganan terlebih dahulu.',
                ], 403);
            }

            $anak = $this->findOwnedChild($request, $childId);
            if (!$anak) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anak tidak ditemukan atau bukan milik Anda',
                ], 403);
            }

            [$start, $end, $period] = $this->resolveSessionPeriod($request);
            $sessions = $this->sessionQuery($anak->id, $start, $end)
                ->orderBy('ended_at')
                ->get();

            $payload     = $this->buildSessionReportPayload($anak, $sessions, $start, $end, $period);
            $moduleBreak = $this->buildModuleSessionBreakdown($sessions);
            $daily       = $this->buildDailySessionBreakdown($sessions, $start, $end);

            // Hitung VAK dan tentukan label dominan
            $vak = $payload['learning_style'];
            if ($vak['has_data']) {
                $scores  = [
                    'Visual'      => $vak['visual'],
                    'Auditori'    => $vak['auditory'],
                    'Kinestetik'  => $vak['kinesthetic'],
                ];
                $vak['dominant'] = array_search(max($scores), $scores);
            } else {
                $vak['dominant'] = '-';
            }

            // Label periode bahasa Indonesia
            Carbon::setLocale('id');
            $periodLabel = $period === 'week'
                ? 'Minggu ' . $start->isoFormat('D MMM') . ' – ' . $end->isoFormat('D MMM YYYY')
                : 'Bulan '  . $start->isoFormat('MMMM YYYY');

            $viewData = [
                'child' => [
                    'nama'      => $anak->nama_anak,
                    'umur'      => $anak->tanggal_lahir ? $anak->tanggal_lahir->age : null,
                    'join_date' => $anak->created_at->locale('id')->isoFormat('D MMMM YYYY'),
                    'cita_cita' => $anak->cita_cita,
                    'hobi'      => $anak->hobi,
                    'makanan_favorit' => $anak->makanan_favorit,
                ],
                'parent_name'   => $user->name,
                'period'        => $payload['period'],
                'periodLabel'   => $periodLabel,
                'printDate'     => now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm'),
                'summary'       => $payload['summary'],
                'learningStyle' => $vak,
                'modules'       => $moduleBreak->toArray(),
                'daily'         => $daily,
                'moods'         => [
                    'summary' => (array) $payload['moods']['summary'],
                    'items'   => collect($payload['moods']['items'])->toArray(),
                ],
                'sessions'      => collect($payload['recent_sessions'])->toArray(),
            ];

            $filename = sprintf(
                'calista-%s-%s-%s.pdf',
                strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $anak->nama_anak)),
                $period,
                $start->format('Ymd')
            );

            $pdf = Pdf::loadView('progress-report', $viewData)
                ->setPaper('a4', 'portrait')
                ->setOption('defaultFont', 'DejaVu Sans')
                ->setOption('isRemoteEnabled', false)
                ->setOption('isHtml5ParserEnabled', true);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('PDF export error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal generate laporan PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/progress/{child_id}/report-email?period=week|month&date=YYYY-MM-DD
     * Send progress report email to the parent.
     */
    public function sendReportEmail(Request $request, $childId)
    {
        try {
            $user = $request->user();
            if (!$user->hasActiveSubscription()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Kirim laporan email adalah fitur premium CALISTA. Silakan berlangganan terlebih dahulu.',
                ], 403);
            }

            $anak = $this->findOwnedChild($request, $childId);
            if (!$anak) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Anak tidak ditemukan atau bukan milik Anda',
                ], 403);
            }

            $period = $request->input('period', 'week');
            if (!in_array($period, ['week', 'month'], true)) {
                $period = 'week';
            }

            $sent = \App\Services\EmailService::sendWeeklyReportEmail(
                $user->email,
                $user->name,
                $anak,
                true, // isManual = true
                $period
            );

            if ($sent) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Laporan belajar berhasil dikirim ke email Anda (' . $user->email . ').',
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengirim email laporan belajar. Silakan coba beberapa saat lagi.',
            ], 500);

        } catch (\Exception $e) {
            Log::error('Report email sending error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengirim email: ' . $e->getMessage(),
            ], 500);
        }
    }
}

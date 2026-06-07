<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 🎓 LEARNING: MoodController — API Mood Tracking
 * 
 * Mengelola data emosi harian anak berdasarkan standar:
 * - UX Child Development 2026: 1x check-in per hari (anti user fatigue)
 * - COPPA: Data minimization (tidak simpan lebih dari yang perlu)
 * 
 * Endpoints:
 *   POST /api/moods          — Simpan mood hari ini
 *   GET  /api/moods/status/{child_id} — Cek apakah hari ini sudah check-in
 *   GET  /api/moods/report/{child_id} — Laporan mood 7 hari (untuk Parent Dashboard)
 */
class MoodController extends Controller
{
    /**
     * POST /api/moods
     * Simpan mood anak untuk hari ini.
     * 
     * Request: { "child_id": 1, "mood_type": "senang", "catatan": null }
     * 
     * 🎓 LEARNING: Kenapa kita cek "sudah check-in hari ini" di backend juga?
     * Defense-in-depth! Frontend cek via GET /status, tapi user bisa bypass Flutter 
     * dan langsung hit API. Backend harus jadi "penjaga terakhir".
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'child_id' => 'required|integer',
                'mood_type' => 'required|in:senang,sedih,penasaran,takut,marah',
                'catatan' => 'nullable|string|max:500',
            ]);

            // Pastikan anak milik user yang login
            $child = Anak::where('id', $validated['child_id'])
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Profil anak tidak ditemukan.',
                ], 404);
            }

            // 🔒 Cek apakah anak sudah check-in mood hari ini
            $alreadyCheckedIn = Mood::todayFor($child->id)->exists();

            if ($alreadyCheckedIn) {
                // Ambil data yang sudah ada untuk dikirim balik
                $existingMood = Mood::todayFor($child->id)->first();
                return response()->json([
                    'status' => 'already_done',
                    'message' => 'Mood hari ini sudah dicatat! Sampai besok ya 😊',
                    'data' => [
                        'mood_type' => $existingMood->mood_type,
                        'recorded_at' => $existingMood->created_at->toISOString(),
                    ],
                ], 200);
            }

            // ✅ Simpan mood baru
            $mood = Mood::create([
                'anak_id' => $child->id,
                'mood_type' => $validated['mood_type'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            Log::info('Mood tracked', [
                'child_id' => $child->id,
                'child_name' => $child->nama_anak,
                'mood' => $mood->mood_type,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Mood '{$mood->mood_type}' berhasil dicatat! Selamat belajar ya 🎉",
                'data' => [
                    'id' => $mood->id,
                    'mood_type' => $mood->mood_type,
                    'recorded_at' => $mood->created_at->toISOString(),
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error storing mood', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan mood.',
            ], 500);
        }
    }

    /**
     * GET /api/moods/status/{child_id}
     * Cek apakah anak sudah check-in mood hari ini.
     * 
     * 🎓 LEARNING: Endpoint ini dipanggil Flutter SETELAH login,
     * sebelum menampilkan Mood Tracking Screen. Jika 'has_checked_in' = true,
     * Flutter langsung skip ke Home Screen.
     */
    public function checkTodayStatus(Request $request, int $childId)
    {
        try {
            // Pastikan anak milik user login
            $child = Anak::where('id', $childId)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Profil anak tidak ditemukan.',
                ], 404);
            }

            $todayMood = Mood::todayFor($childId)->first();

            return response()->json([
                'status' => 'success',
                'has_checked_in' => $todayMood !== null,
                'data' => $todayMood ? [
                    'mood_type' => $todayMood->mood_type,
                    'recorded_at' => $todayMood->created_at->toISOString(),
                ] : null,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error checking mood status', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengecek status mood.',
            ], 500);
        }
    }

    /**
     * GET /api/moods/report/{child_id}
     * Laporan mood 7 hari terakhir (untuk Parent Dashboard).
     * 
     * 🎓 LEARNING: Data ini divisualisasikan ke orang tua supaya mereka bisa
     * memahami pola emosi anak dan kapan anak paling siap belajar.
     */
    public function report(Request $request, int $childId)
    {
        try {
            $child = Anak::where('id', $childId)
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$child) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Profil anak tidak ditemukan.',
                ], 404);
            }

            // Ambil mood 7 hari terakhir
            $moods = Mood::where('anak_id', $childId)
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($mood) {
                    return [
                        'mood_type' => $mood->mood_type,
                        'date' => $mood->created_at->toDateString(),
                        'time' => $mood->created_at->format('H:i'),
                    ];
                });

            // Summary: selalu kirim 5 slot tetap agar Flutter chart stabil.
            $summary = $this->normalizeMoodSummary(
                $moods->groupBy('mood_type')->map->count()
            );

            // 🧠 NEW: INFERENCE LOGIC (If no moods recorded)
            // Boss requirement: logic kalkulasi matematik berdasarkan histori game
            return response()->json([
                'status' => 'success',
                'data' => [
                    'child_name' => $child->nama_anak,
                    'period' => '7 hari terakhir',
                    'moods' => $moods,
                    'summary' => $summary,
                    'dominant_mood' => $summary->max() > 0
                        ? $summary->sortDesc()->keys()->first()
                        : null,
                    'is_inferred' => false,
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching mood report', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil laporan mood.',
            ], 500);
        }
    }

    /**
     * 🧠 Inferensi Mood dari Progres Belajar (V-A-K Correlation)
     * 
     * Logic:
     * - Avg Stars > 2.5 && Score > 80 -> Senang (Sukses belajar)
     * - Score < 60 -> Penasaran (Sedang mencoba keras)
     * - Bintang 1-2 -> Sedih (Butuh bantuan)
     */
    private function normalizeMoodSummary($summary)
    {
        $keys = ['senang', 'penasaran', 'takut', 'sedih', 'marah'];
        return collect($keys)->mapWithKeys(function ($key) use ($summary) {
            return [$key => (int) ($summary[$key] ?? 0)];
        });
    }
}

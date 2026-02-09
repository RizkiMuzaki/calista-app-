<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use App\Models\ProgresAnak;
use App\Models\Level;
use App\Models\Module;
use App\Models\WritingItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgresAnakController extends Controller
{
    /**
     * Simpan atau update progress menulis
     */
    public function saveWritingProgress(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
            'score' => 'required|integer|min:0|max:100',
            'bintang' => 'required|integer|min:0|max:3',
            'writing_item_id' => 'required|exists:writing_items,id',
        ]);

        $user = Auth::user();
        
        $anak = $user->anaks()->where('is_active', true)->first();
        
        if (!$anak) {
            $anak = $user->anaks()->first();
        }
        
        if (!$anak) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada profil anak ditemukan untuk user ini'
            ], 404);
        }

        $progres = ProgresAnak::where('anak_id', $anak->id)
            ->where('level_id', $request->level_id)
            ->first();

        if ($progres) {
            if ($request->score > $progres->score) {
                $progres->update([
                    'score' => $request->score,
                    'bintang' => $request->bintang,
                    'selesai' => $request->score >= 70
                ]);
            }
        } else {
            $progres = ProgresAnak::create([
                'anak_id' => $anak->id,
                'level_id' => $request->level_id,
                'score' => $request->score,
                'bintang' => $request->bintang,
                'selesai' => $request->score >= 70
            ]);
        }

        $this->saveWritingItemDetail($anak->id, $request->writing_item_id, $request->score);

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil disimpan',
            'data' => $progres,
            'isCompleted' => $progres->selesai,
            'anak_id' => $anak->id
        ]);
    }

    /**
     * Ambil progress anak untuk level tertentu
     */
    public function getProgress(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id'
        ]);

        $user = Auth::user();
        
        $anak = $user->anaks()->where('is_active', true)->first();
        
        if (!$anak) {
            $anak = $user->anaks()->first();
        }
        
        if (!$anak) {
            return response()->json([
                'success' => false,
                'data' => null
            ]);
        }

        $progres = ProgresAnak::where('anak_id', $anak->id)
            ->where('level_id', $request->level_id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $progres,
            'anak_id' => $anak->id
        ]);
    }

    /**
     * Simpan detail item writing yang dikerjakan (opsional)
     */
    private function saveWritingItemDetail($anakId, $writingItemId, $score)
    {
        // Jika Anda punya tabel untuk menyimpan detail per item
        // Misalnya: anak_writing_progress
        // Anda bisa implementasikan di sini
        
        // Contoh:
        // \App\Models\AnakWritingProgress::updateOrCreate(
        //     [
        //         'anak_id' => $anakId,
        //         'writing_item_id' => $writingItemId
        //     ],
        //     [
        //         'score' => $score,
        //         'attempt_count' => \DB::raw('attempt_count + 1'),
        //         'last_attempt_at' => now()
        //     ]
        // );
    }

    /**
     * Lanjutkan ke level berikutnya
     */
    public function continueToNext(Request $request)
    {
        $request->validate([
            'current_level_id' => 'required|exists:levels,id',
            'score' => 'required|integer',
            'bintang' => 'required|integer',
            'writing_item_id' => 'required|exists:writing_items,id'
        ]);

        $user = Auth::user();
        
        $anak = $user->anaks()->where('is_active', true)->first();
        
        if (!$anak) {
            $anak = $user->anaks()->first();
        }
        
        if (!$anak) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada profil anak ditemukan'
            ], 404);
        }

        ProgresAnak::updateOrCreate(
            [
                'anak_id' => $anak->id,
                'level_id' => $request->current_level_id
            ],
            [
                'score' => $request->score,
                'bintang' => $request->bintang,
                'selesai' => $request->score >= 70
            ]
        );

        $currentLevel = Level::find($request->current_level_id);
        $nextLevel = Level::where('module_id', $currentLevel->module_id)
            ->where('order_number', '>', $currentLevel->order_number)
            ->orderBy('order_number')
            ->first();

        if ($nextLevel) {
            return response()->json([
                'success' => true,
                'next_level' => $nextLevel,
                'next_writing_item' => $nextLevel->writingItems()->first(),
                'message' => 'Berhasil lanjut ke level berikutnya'
            ]);
        }

        return response()->json([
            'success' => true,
            'next_level' => null,
            'message' => 'Selamat! Anda telah menyelesaikan semua level'
        ]);
    }

    public function showProgressGraphics()
    {
        $activeChild = Anak::where('user_id', Auth::id())
                           ->where('is_active', true)
                           ->firstOrFail();

        $totalProgress = $this->getOverallProgress($activeChild->id);
        $moduleProgressChart = $this->getModuleProgressChartData($activeChild->id);

        $detailProgress = ProgresAnak::where('anak_id', $activeChild->id)
                                     ->with('level')
                                     ->orderBy('updated_at', 'desc')
                                     ->get();

        return view('pages.progres-grafik', compact(
            'activeChild',
            'totalProgress',
            'moduleProgressChart',
            'detailProgress'
        ));
    }

    private function getOverallProgress($anakId)
    {
        $total = ProgresAnak::where('anak_id', $anakId)->count();
        
        $completed = ProgresAnak::where('anak_id', $anakId)
                               ->where('selesai', true)
                               ->count();

        $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
        $avgScore = ProgresAnak::where('anak_id', $anakId)
                              ->avg('score') ?? 0;

        return [
            'completed' => $completed,
            'total' => $total,
            'percentage' => round($percentage, 1),
            'avgScore' => round($avgScore, 1)
        ];
    }

    private function getModuleProgressChartData($anakId)
    {
        $modules = Module::with(['levels' => function ($q) use ($anakId) {
            $q->whereHas('progresAnaks', function ($q2) use ($anakId) {
                $q2->where('anak_id', $anakId);
            });
        }])->get();

        $labels = [];
        $completed = [];
        $total = [];

        foreach ($modules as $module) {
            $moduleTotal = ProgresAnak::where('anak_id', $anakId)
                                     ->whereHas('level', function ($q) use ($module) {
                                         $q->where('module_id', $module->id);
                                     })
                                     ->count();
            
            if ($moduleTotal > 0) {
                $labels[] = $module->name;
                $moduleCompleted = ProgresAnak::where('anak_id', $anakId)
                                             ->whereHas('level', function ($q) use ($module) {
                                                 $q->where('module_id', $module->id);
                                             })
                                             ->where('selesai', true)
                                             ->count();
                $completed[] = $moduleCompleted;
                $total[] = $moduleTotal;
            }
        }

        return [
            'labels' => $labels,
            'completed' => $completed,
            'total' => $total
        ];
    }
}
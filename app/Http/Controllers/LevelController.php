<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Module;
use App\Models\CountingItem;
use App\Models\WritingItems;
use App\Models\ProgresAnak;
use Illuminate\Http\Request;
use App\Services\TypecastService;
use App\Models\PuzzleItem;

class LevelController extends Controller
{
    /**
     * Show specific level detail
     */
    public function show(Request $request, $slug, $level)
    {
        $user = auth()->user();
        $activeChild = $user->anaks()->where('is_active', true)->first();
        
        // Fallback jika tidak ada active child
        if (!$activeChild) {
            $activeChild = $user->anaks()->first();
        }
        
        // Get module
        $module = Module::where('slug', $slug)->first();
        if (!$module) {
            abort(404, 'Module tidak ditemukan');
        }
        
        // Get level
        $levelData = Level::where('id', $level)
            ->where('module_id', $module->id)
            ->first();
        
        if (!$levelData) {
            abort(404, 'Level tidak ditemukan');
        }
        
        // CEK APAKAH LEVEL TERKUNCI
        $isLocked = $this->isLevelLocked($activeChild, $levelData);
        if ($isLocked) {
            abort(403, 'Level ini masih terkunci. Selesaikan level sebelumnya terlebih dahulu!');
        }
        
        // Get age group
        $ageGroup = '5-7';
        if ($activeChild && $activeChild->usia) {
            $ageGroup = $this->getAgeGroup($activeChild->usia);
        }
        
        // Get all levels for this module
        $levels = Level::where('module_id', $module->id)
            ->orderBy('order_number')
            ->get();
        
        // Check progress for each level
        $progressMap = [];
        foreach ($levels as $lvl) {
            $progress = ProgresAnak::where('anak_id', $activeChild->id)
                ->where('level_id', $lvl->id)
                ->first();
            $progressMap[$lvl->id] = $progress ? $progress->selesai : false;
        }
        
        // Tentukan view berdasarkan tipe module
        if ($module->type === 'counting' || $module->type === 'hitung') {
            // Get counting items
            $countingItems = CountingItem::where('level_id', $levelData->id)
                ->where('is_active', true)
                ->get();
            
            $audioGreeting = $this->generateGreetingAudio($activeChild, $module, $levelData);
            
            return view('pages.menghitung', [
                'activeChild' => $activeChild,
                'module' => $module,
                'level' => $levelData,
                'countingItems' => $countingItems,
                'ageGroup' => $ageGroup,
                'audioGreeting' => $audioGreeting,
                'greetingText' => "Halo! Selamat datang di level menghitung. Ayo kita belajar berhitung bersama!",
                'serverUrl' => env('PYTHON_VOICE_SERVER_URL', 'http://localhost:5000'),
                'progressMap' => $progressMap // Pass progress map to the view
            ]);
        }
        
        // ...existing code...
    }
    
    /**
     * CEK APAKAH LEVEL TERKUNCI
     */
    private function isLevelLocked($activeChild, $levelData)
    {
        if (!$activeChild) {
            return true;
        }
        
        // Level pertama TIDAK PERNAH TERKUNCI (SELALU TERBUKA)
        if ($levelData->order_number === 1) {
            return false;
        }
        
        // Cek apakah level sebelumnya sudah selesai
        $previousLevel = Level::where('module_id', $levelData->module_id)
            ->where('order_number', $levelData->order_number - 1)
            ->first();
        
        if (!$previousLevel) {
            return false;
        }
        
        // Cek progress level sebelumnya dari tabel progres_anaks
        $previousProgress = ProgresAnak::where('anak_id', $activeChild->id)
            ->where('level_id', $previousLevel->id)
            ->where('selesai', true)
            ->first();
        
        // Jika TIDAK ADA progress yang SELESAI, level terkunci
        return !$previousProgress;
    }
    
    /**
     * Helper: Get age group from age
     */
    private function getAgeGroup($age)
    {
        $age = (int) $age;
        if ($age >= 3 && $age <= 5) {
            return '3-5';
        } elseif ($age >= 5 && $age <= 7) {
            return '5-7';
        } elseif ($age >= 7 && $age <= 9) {
            return '7-9';
        } elseif ($age >= 9 && $age <= 12) {
            return '9-12';
        }
        return '5-7';
    }
    
    /**
     * Complete a level
     */
    public function complete(Request $request, $slug, $level)
    {
        $request->validate([
            'score' => 'required|integer',
            'stars' => 'required|integer|min:1|max:3',
            'correct_count' => 'required|integer'
        ]);
        
        $module = Module::where('slug', $slug)->firstOrFail();
        $level = Level::where('id', $level)
                     ->where('module_id', $module->id)
                     ->firstOrFail();
        
        $anakAktif = auth()->user()->anaks()->where('is_active', true)->firstOrFail();
        
        // Simpan atau update progres
        ProgresAnak::updateOrCreate(
            ['anak_id' => $anakAktif->id, 'level_id' => $level->id],
            [
                'score' => $request->score,
                'bintang' => $request->stars,
                'selesai' => true
            ]
        );
        
        // Generate audio pujian akhir
        $typecastService = new TypecastService();
        $completionMessage = "Selamat! Kamu telah menyelesaikan level ini dengan skor {$request->score}! ";
        $completionMessage .= "Kamu berhasil menjawab {$request->correct_count} soal dengan benar. ";
        $completionMessage .= "Mantap sekali!";
        
        $audioCompletion = null;
        try {
            $result = $typecastService->generateGreetingAudio(
                auth()->user()->name ?? 'Teman',
                $module->name,
                "Level {$level->order_number} Selesai",
                $completionMessage
            );
            
            if ($result['success']) {
                $audioCompletion = $result['url'];
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate completion audio: ' . $e->getMessage());
        }
        
        // Cek apakah ada level berikutnya
        $nextLevel = Level::where('module_id', $module->id)
            ->where('order_number', '>', $level->order_number)
            ->orderBy('order_number')
            ->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Level berhasil diselesaikan!',
            'audio_completion' => $audioCompletion,
            'completion_message' => $completionMessage,
            'next_level' => $nextLevel ? [
                'id' => $nextLevel->id,
                'order_number' => $nextLevel->order_number,
                'name' => $nextLevel->name
            ] : null
        ]);
    }

    /**
     * Show writing practice for a specific item
     */
    public function showWriting($slug, Level $level, WritingItems $writingItem)
    {
        $module = Module::where('slug', $slug)->firstOrFail();
        
        $typecastService = new TypecastService();
        $audioGreeting = null;
        
        try {
            $greetingResult = $typecastService->generateGreetingAudio(
                auth()->user()->name,
                $module->name,
                "Level {$level->order_number}",
                $writingItem->type === 'letter' 
                    ? "Kita akan belajar menulis huruf {$writingItem->text}. Ayo ikuti garis panduannya dengan baik!"
                    : "Kita akan belajar menulis kata '{$writingItem->text}'. Pastikan setiap hurufnya rapi ya!"
            );
            
            if ($greetingResult['success']) {
                $audioGreeting = $greetingResult['url'];
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate greeting: ' . $e->getMessage());
        }
        
        $greetingText = $writingItem->type === 'letter' 
            ? "Halo! Ayo belajar menulis huruf {$writingItem->text}!"
            : "Halo! Ayo belajar menulis kata '{$writingItem->text}'!";
        
        return view('pages.menulis', [
            'writingItem' => $writingItem,
            'level' => $level,
            'module' => $module,
            'audioGreeting' => $audioGreeting,
            'greetingText' => $greetingText
        ]);
    }

    /**
     * Show counting game for a specific level
     */
    public function showCounting($slug, $level)
    {
        $module = Module::where('slug', $slug)->firstOrFail();
        $level = Level::where('id', $level)
                     ->where('module_id', $module->id)
                     ->firstOrFail();
        
        $countingItems = CountingItem::where('level_id', $level->id)
                        ->orderBy('id')
                        ->get();
        
        // Generate greeting audio untuk permainan menghitung
        $typecastService = new TypecastService();
        $audioGreeting = null;
        $greetingText = "";
        
        try {
            $firstItem = $countingItems->first();
            $greetingMessage = "Kita akan belajar berhitung dengan gambar {$firstItem->nama_objek}. ";
            $greetingMessage .= "Tarik gambar ke area kanan untuk berlatih berhitung!";
            
            $greetingResult = $typecastService->generateGreetingAudio(
                auth()->user()->name ?? 'Teman',
                $module->name,
                "Level {$level->order_number} - Menghitung",
                $greetingMessage
            );
            
            if ($greetingResult['success']) {
                $audioGreeting = $greetingResult['url'];
            }
            
            $greetingText = $greetingMessage;
        } catch (\Exception $e) {
            \Log::error('Failed to generate counting greeting: ' . $e->getMessage());
        }
        
        return view('pages.menghitung', [
            'module' => $module,
            'level' => $level,
            'countingItems' => $countingItems,
            'audioGreeting' => $audioGreeting,
            'greetingText' => $greetingText,
            'title' => 'Menghitung - Level ' . $level->order_number . ' - Calista'
        ]);
    }

    /**
     * API untuk generate suara pujian saat berhasil menghitung
     */
    public function generateSuccessAudio(Request $request, $slug, $level)
    {
        $request->validate([
            'hasil' => 'required|integer',
            'nama_objek' => 'required|string',
            'jenis_operasi' => 'required|in:tambah,kurang',
            'nilai_kiri' => 'required|integer',
            'nilai_kanan' => 'required|integer'
        ]);
        
        $hasil = $request->hasil;
        $nama_objek = $request->nama_objek;
        $jenis_operasi = $request->jenis_operasi;
        $nilai_kiri = $request->nilai_kiri;
        $nilai_kanan = $request->nilai_kanan;
        
        $typecastService = new TypecastService();
        
        // Buat pesan pujian
        $operationText = $jenis_operasi === 'tambah' ? 'ditambah' : 'dikurangi';
        $praiseMessage = "Wahhh, berhasil! {$nilai_kiri} {$operationText} {$nilai_kanan} sama dengan {$hasil} {$nama_objek}! ";
        $praiseMessage .= "Hebat sekali! Lanjutkan ke soal berikutnya!";
        
        try {
            $result = $typecastService->generateGreetingAudio(
                auth()->user()->name ?? 'Teman',
                'Permainan Menghitung',
                'Soal Berhasil',
                $praiseMessage
            );
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'audio_url' => $result['url'],
                    'message' => $praiseMessage
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate success audio: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal generate audio'
        ], 500);
    }

    /**
     * API untuk Typecast TTS
     */
    public function typecastTTS(Request $request, $slug, $level)
    {
        $request->validate([
            'text' => 'required|string|max:500'
        ]);
        
        $typecastService = new TypecastService();
        
        try {
            $result = $typecastService->generateTTS($request->text);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'audio_url' => $result['url'],
                    'text' => $request->text
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Typecast TTS error: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal generate TTS'
        ], 500);
    }

    /**
     * Show puzzle game for a specific level
     */
    public function showPuzzle($slug, $level)
    {
        $module = Module::where('slug', $slug)->firstOrFail();
        $level = Level::where('id', $level)
                     ->where('module_id', $module->id)
                     ->firstOrFail();
        
        $puzzleItem = PuzzleItem::where('level_id', $level->id)
                        ->where('is_active', true)
                        ->firstOrFail();
        
        return view('pages.puzzle', [
            'module' => $module,
            'level' => $level,
            'puzzleItem' => $puzzleItem,
            'title' => 'Puzzle - Level ' . $level->order_number . ' - ' . $module->name . ' - Calista'
        ]);
    }

    /**
     * Redirect to counting page using the first level of the given module.
     */
    public function redirectToCounting($slug)
    {
        $module = Module::where('slug', $slug)->firstOrFail();

        $level = Level::where('module_id', $module->id)
                      ->orderBy('order_number')
                      ->firstOrFail();

        return redirect()->route('calista.level.counting', [
            'slug' => $module->slug,
            'level' => $level->id,
        ]);
    }

    /**
     * Redirect to puzzle page using the first level with puzzle of the given module.
     */
    public function redirectToPuzzle($slug)
    {
        $module = Module::where('slug', $slug)->firstOrFail();

        $level = Level::where('module_id', $module->id)
                      ->whereHas('puzzleItems', function($query) {
                          $query->where('is_active', true);
                      })
                      ->orderBy('order_number')
                      ->firstOrFail();

        return redirect()->route('calista.level.puzzle', [
            'slug' => $module->slug,
            'level' => $level->id,
        ]);
    }

    /**
     * Submit puzzle score
     */
    public function submitPuzzle(Request $request, $slug, $level)
    {
        $request->validate([
            'score' => 'required|integer',
            'time_remaining' => 'required|integer',
            'pieces_correct' => 'required|integer',
            'total_pieces' => 'required|integer'
        ]);
        
        $module = Module::where('slug', $slug)->firstOrFail();
        $level = Level::where('id', $level)
                     ->where('module_id', $module->id)
                     ->firstOrFail();
        
        $anakAktif = auth()->user()->anaks()->where('is_active', true)->firstOrFail();
        
        // Hitung bintang berdasarkan skor
        $stars = $request->score >= 80 ? 3 : ($request->score >= 60 ? 2 : 1);
        
        // Simpan progres puzzle
        ProgresAnak::updateOrCreate(
            ['anak_id' => $anakAktif->id, 'level_id' => $level->id],
            [
                'score' => $request->score,
                'bintang' => $stars,
                'selesai' => true
            ]
        );
        
        // Cek level berikutnya
        $nextLevel = Level::where('module_id', $module->id)
            ->where('order_number', '>', $level->order_number)
            ->orderBy('order_number')
            ->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Skor puzzle berhasil disimpan!',
            'next_level' => $nextLevel ? [
                'id' => $nextLevel->id,
                'order_number' => $nextLevel->order_number
            ] : null,
            'data' => [
                'score' => $request->score,
                'time_bonus' => floor($request->time_remaining / 10) * 50,
                'accuracy' => round(($request->pieces_correct / $request->total_pieces) * 100, 2),
                'stars' => $stars
            ]
        ]);
    }

    /**
     * Show module detail with all levels
     */
    public function showModule($slug)
    {
        $user = auth()->user();
        $activeChild = $user->anaks()->where('is_active', true)->first();
        
        if (!$activeChild) {
            $activeChild = $user->anaks()->first();
        }
        
        $module = Module::where('slug', $slug)->first();
        if (!$module) {
            abort(404, 'Module tidak ditemukan');
        }
        
        // Get all levels for this module
        $levels = Level::where('module_id', $module->id)
            ->orderBy('order_number')
            ->get();
        
        // Build progress map
        $progresMap = [];
        $progresDetails = [];
        
        if ($activeChild) {
            $progresses = ProgresAnak::where('anak_id', $activeChild->id)
                ->whereIn('level_id', $levels->pluck('id'))
                ->get();
            
            foreach ($progresses as $progres) {
                // Key: level_id, Value: selesai (boolean/0/1)
                $progresMap[$progres->level_id] = (bool) $progres->selesai;
                $progresDetails[$progres->level_id] = $progres;
            }
        }
        
        return view('pages.module-detail', [
            'module' => $module,
            'levels' => $levels,
            'anakAktif' => $activeChild,
            'progresMap' => $progresMap,
            'progresDetails' => $progresDetails
        ]);
    }
}
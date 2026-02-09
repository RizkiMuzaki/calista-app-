<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnakController extends Controller
{
    // Tampilkan form input data anak
    public function create()
    {
        return view('pages.isidatanak');
    }
    
    // Simpan data anak
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_anak' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
        ], [
            'nama_anak.required' => 'Nama anak harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;
        $validated['limit_detik'] = 3600;
        $validated['sisa_detik'] = 3600;
        $validated['tanggal_reset'] = now()->toDateString();
        
        $anak = Anak::create($validated);
        
        return redirect()->route('anak.profile')->with('success', 'Data anak berhasil disimpan!');
    }
    
    // Show list of children with profile
    public function index()
    {
        $anaks = Anak::where('user_id', Auth::id())->get();
        $activeChild = Anak::where('user_id', Auth::id())
                           ->where('is_active', true)
                           ->first();
        
        return view('pages.daataanak', compact('anaks', 'activeChild'));
    }

    // Show child profile page
    public function profile()
    {
        $anaks = Anak::where('user_id', Auth::id())->get();
        $activeChild = Anak::where('user_id', Auth::id())
                           ->where('is_active', true)
                           ->first();
        
        // Update timer sebelum ditampilkan
        if ($activeChild) {
            $activeChild->checkAndResetDaily();
            $activeChild->updateRunningTimer();
            
            // Ambil data progress
            $progressByType = $activeChild->getAllProgress();
            $progressByModule = $activeChild->getProgressByModule();
            $recentProgress = $activeChild->getRecentProgress();
        } else {
            $progressByType = null;
            $progressByModule = null;
            $recentProgress = null;
        }
        
        return view('pages.profilprogreanak', compact(
            'anaks',
            'activeChild',
            'progressByType',
            'progressByModule',
            'recentProgress'
        ));
    }

    // Set active child
    public function setActive(Request $request, $id)
    {
        $anak = Anak::findOrFail($id);

        if ($anak->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        // Jika request ada 'deactivate', nonaktifkan anak ini
        if ($request->has('deactivate') && $request->deactivate) {
            $anak->update(['is_active' => false]);
            return response()->json(['success' => true, 'message' => 'Anak dinonaktifkan']);
        }

        // Deactivate all children
        Anak::where('user_id', Auth::id())->update(['is_active' => false]);
        // Activate selected child
        $anak->update(['is_active' => true]);

        return response()->json(['success' => true, 'message' => 'Profil anak ' . $anak->nama_anak . ' telah diaktifkan']);
    }

    // Update limit time
    public function updateLimit(Request $request, $id)
    {
        $anak = Anak::findOrFail($id);
        
        if ($anak->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $validated = $request->validate([
            'limit_detik' => 'required|integer|min:60|max:28800',
        ]);

        $anak->update([
            'limit_detik' => $validated['limit_detik'],
            'sisa_detik' => $validated['limit_detik'],
            'tanggal_reset' => now()->toDateString(),
            'timer_started_at' => null,
            'timer_last_updated' => null
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Limit waktu berhasil diperbarui',
            'remaining_seconds' => $anak->sisa_detik
        ]);
    }

    // API untuk mendapatkan waktu tersisa - ALWAYS CALCULATE FROM SERVER
    public function getRemainingTime($id)
    {
        $anak = Anak::findOrFail($id);
        
        if ($anak->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        // 1. Cek dan reset harian jika perlu
        $anak->checkAndResetDaily();
        
        // 2. Jika timer sedang berjalan, hitung real-time dari server TANPA menyimpan setiap kali
        $remainingTime = $anak->sisa_detik;
        if ($anak->timer_started_at) {
            $now = Carbon::now();
            $referenceTime = $anak->timer_last_updated ?? $anak->timer_started_at;
            $elapsedSeconds = $referenceTime->diffInRealSeconds($now, false);
            
            // Hitung sisa hanya untuk display, tidak simpan ke DB
            $remainingTime = max(0, $anak->sisa_detik - floor($elapsedSeconds));
            
            // HANYA update DB ketika elapsed >= 1 second untuk real
            if ($elapsedSeconds >= 1.0) {
                $anak->updateRunningTimer();
                $remainingTime = $anak->sisa_detik;
            }
        }
        
        // 3. Return data yang sudah ter-update dari database
        return response()->json([
            'success' => true,
            'remaining_seconds' => (int)$remainingTime,
            'formatted_time' => $this->formatRemainingTime($remainingTime),
            'has_time' => $remainingTime > 0,
            'timer_started_at' => $anak->timer_started_at,
            'limit_detik' => (int)$anak->limit_detik,
            'is_running' => (bool)$anak->timer_started_at,
            'timestamp' => Carbon::now()->timestamp,
            'server_time' => Carbon::now()->timestamp  // Untuk background timer tracking
        ]);
    }

    // Helper method untuk format waktu
    private function formatRemainingTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }
        
        return sprintf('%02d:%02d', $minutes, $secs);
    }

    // API untuk memulai timer
    public function startTimer($id)
    {
        $anak = Anak::findOrFail($id);
        
        if ($anak->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $anak->checkAndResetDaily();
        $anak->startTimer();
        
        return response()->json([
            'success' => true,
            'message' => 'Timer dimulai',
            'remaining_seconds' => $anak->sisa_detik,
            'formatted_time' => $anak->getFormattedRemainingTime()
        ]);
    }

    // API untuk menghentikan timer
    public function stopTimer($id)
    {
        $anak = Anak::findOrFail($id);
        
        if ($anak->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $anak->stopTimer();
        
        return response()->json([
            'success' => true,
            'message' => 'Timer dihentikan',
            'remaining_seconds' => $anak->sisa_detik,
            'formatted_time' => $anak->getFormattedRemainingTime()
        ]);
    }

    // API untuk reset timer
    public function resetTimer($id)
    {
        $anak = Anak::findOrFail($id);
        
        if ($anak->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $anak->resetTimer();
        
        return response()->json([
            'success' => true,
            'message' => 'Timer direset',
            'remaining_seconds' => $anak->sisa_detik,
            'formatted_time' => $anak->getFormattedRemainingTime()
        ]);
    }

    // Deduct time (untuk digunakan di game/aktivitas)
    public function deductTime(Request $request, $id)
    {
        $anak = Anak::findOrFail($id);
        
        if ($anak->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $validated = $request->validate([
            'seconds' => 'required|integer|min:1|max:3600',
        ]);

        $remaining = $anak->deductTime($validated['seconds']);
        
        return response()->json([
            'success' => true,
            'message' => 'Waktu berkurang ' . $validated['seconds'] . ' detik',
            'remaining_seconds' => $remaining,
            'formatted_time' => $anak->getFormattedRemainingTime()
        ]);
    }

    // Cek status anak aktif - untuk validasi akses permainan
    public function checkActiveChildStatus()
    {
        $activeChild = Anak::where('user_id', Auth::id())
                           ->where('is_active', true)
                           ->first();

        if (!$activeChild) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih anak terlebih dahulu atau aktifkan profil anak',
                'active_child' => null
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Anak aktif ditemukan',
            'active_child' => [
                'id' => $activeChild->id,
                'nama_anak' => $activeChild->nama_anak,
                'is_active' => $activeChild->is_active,
                'sisa_detik' => $activeChild->sisa_detik,
                'formatted_time' => $activeChild->getFormattedRemainingTime()
            ]
        ]);
    }

    // Redirect ke permainan dengan cek status anak
    public function redirectToPermainan()
    {
        $activeChild = Anak::where('user_id', Auth::id())
                           ->where('is_active', true)
                           ->first();

        // Jika tidak ada anak yang aktif
        if (!$activeChild) {
            return redirect()->route('anak.profile')
                           ->with('error', 'Silakan pilih dan aktifkan profil anak terlebih dahulu!');
        }

        // Jika anak aktif tetapi timer belum dimulai (field timer masih null)
        if (is_null($activeChild->timer_started_at) || is_null($activeChild->timer_last_updated)) {
            return redirect()->route('anak.profile')
                           ->with('error', 'Timer belum diinisialisasi. Silakan mulai timer terlebih dahulu!');
        }

        // Jika anak aktif, timer sudah dimulai, dan punya waktu, tampilkan halaman permainan
        return $this->showPermainan();
    }

    // Menampilkan halaman permainan dengan real-time check
    public function showPermainan()
    {
        $activeChild = Anak::where('user_id', Auth::id())
                           ->where('is_active', true)
                           ->first();

        if (!$activeChild) {
            return redirect()->route('anak.profile')
                           ->with('error', 'Anak tidak ditemukan');
        }

        // Update timer terkini
        $activeChild->checkAndResetDaily();
        $activeChild->updateRunningTimer();

        // Get all modules untuk ditampilkan
        $modules = \App\Models\Module::all();
        
        // Get all active games untuk ditampilkan
        $games = \App\Models\Game::where('status', 1)->get();

        return view('pages.permainan', [
            'activeChild' => $activeChild,
            'remaining_seconds' => (int)$activeChild->sisa_detik,
            'formatted_time' => $activeChild->getFormattedRemainingTime(),
            'child_id' => $activeChild->id,
            'modules' => $modules,
            'games' => $games
        ]);
    }

    // Menampilkan halaman selesai/completion
    public function showSelesai()
    {
        $activeChild = Anak::where('user_id', Auth::id())
                           ->where('is_active', true)
                           ->first();

        if (!$activeChild) {
            return redirect()->route('anak.profile')
                           ->with('error', 'Anak tidak ditemukan');
        }

        return view('pages.selesai', [
            'activeChild' => $activeChild
        ]);
    }
}
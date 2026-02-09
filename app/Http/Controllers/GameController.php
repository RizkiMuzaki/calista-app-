<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::where('status', 1)->get();
        return view('games.index', compact('games'));
    }

    /**
     * Show the glass game for a specific game
     */
    public function showGlassGame(Game $game)
    {
        // Load hadiahs relationship
        $game->load('hadiahs');
        
        // Filter hanya hadiah dengan status aktif (jika ada kolom status)
        $availablePrizes = $game->hadiahs->filter(function ($hadiah) {
            // Jika ada kolom status, filter yang status=1
            if (isset($hadiah->status)) {
                return $hadiah->status == 1;
            }
            return true; // Jika tidak ada kolom status, ambil semua
        });
        
        // Jika tidak ada hadiah yang tersedia
        if ($availablePrizes->isEmpty()) {
            // Return view kosong atau redirect ke halaman sebelumnya
            return redirect()->back()->with('error', 'Game ini belum memiliki hadiah yang tersedia.');
        }
        
        // Pass game data to view
        $gameData = [
            'id' => $game->id,
            'nama_game' => $game->nama_game,
            'foto' => $game->foto,
            'status' => $game->status,
            'hadiahs' => $availablePrizes->map(function ($hadiah) {
                return [
                    'id' => $hadiah->id,
                    'game_id' => $hadiah->game_id,
                    'nama_hadiah' => $hadiah->nama_hadiah,
                    'jenis_hadiah' => $hadiah->jenis_hadiah,
                    'foto' => $hadiah->foto,
                    'audio' => $hadiah->audio,
                    'stok' => $hadiah->stok ?? 1, // Default stok 1 jika tidak ada
                ];
            })->toArray(),
        ];

        return view('pages.gamegelas', compact('gameData'));
    }

    /**
     * API untuk menyimpan hadiah yang dipilih
     */
    public function saveSelectedPrizes(Game $game)
    {
        try {
            $selectedPrizeIds = request()->input('selected_prizes', []);
            
            if (count($selectedPrizeIds) < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal 3 hadiah'
                ], 400);
            }
            
            if (count($selectedPrizeIds) > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 5 hadiah yang bisa dipilih'
                ], 400);
            }
            
            // Load game dan hadiahnya
            $game->load('hadiahs');
            
            // Validate bahwa semua hadiah yang dipilih ada di game ini
            $selectedPrizes = $game->hadiahs->whereIn('id', $selectedPrizeIds);
            
            if ($selectedPrizes->count() !== count($selectedPrizeIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa hadiah tidak ditemukan'
                ], 404);
            }
            
            // Persiapkan hadiah yang akan ditampilkan di gelas (max 3)
            $displayedPrizes = [];
            foreach ($selectedPrizes->take(3) as $prize) {
                $displayedPrizes[] = [
                    'id' => $prize->id,
                    'nama_hadiah' => $prize->nama_hadiah,
                    'jenis_hadiah' => $prize->jenis_hadiah,
                    'foto' => $prize->foto ? asset('storage/' . $prize->foto) : null,
                    'audio' => $prize->audio ? asset('storage/' . $prize->audio) : null,
                ];
            }
            
            // Simpan ke session untuk digunakan di tahap selanjutnya
            session([
                'game_selected_prizes_' . $game->id => $selectedPrizeIds,
                'game_' . $game->id . '_data' => $selectedPrizes->toArray()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Hadiah berhasil disimpan',
                'prizes_count' => count($selectedPrizeIds),
                'displayed_prizes' => $displayedPrizes
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan hadiah yang dipilih (random)
     */
    public function getSelectedPrizes(Game $game)
    {
        try {
            // Coba ambil dari session dulu
            $selectedPrizeIds = session('game_selected_prizes_' . $game->id, 
                                       request()->input('selected_prizes', []));
            
            if (count($selectedPrizeIds) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal 2 hadiah'
                ], 400);
            }
            
            // Ambil game dan hadiahnya
            $game->load('hadiahs');
            
            // Filter hadiah yang dipilih
            $selectedPrizes = $game->hadiahs->whereIn('id', $selectedPrizeIds);
            
            if ($selectedPrizes->count() < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa hadiah tidak ditemukan'
                ], 404);
            }
            
            // Acak hadiah
            $randomPrize = $selectedPrizes->random();
            
            // Kurangi stok jika ada (opsional)
            if (isset($randomPrize->stok) && $randomPrize->stok > 0) {
                $randomPrize->decrement('stok');
            }
            
            // Clear session setelah selesai
            session()->forget('game_selected_prizes_' . $game->id);
            session()->forget('game_' . $game->id . '_data');
            
            // Log hasil game (opsional, jika ada tabel history)
            // $this->logGameResult($game->id, $randomPrize->id, auth()->id());
            
            return response()->json([
                'success' => true,
                'prizes' => [
                    [
                        'id' => $randomPrize->id,
                        'nama_hadiah' => $randomPrize->nama_hadiah,
                        'jenis_hadiah' => $randomPrize->jenis_hadiah,
                        'foto' => $randomPrize->foto ? asset('storage/' . $randomPrize->foto) : null,
                        'audio' => $randomPrize->audio ? asset('storage/' . $randomPrize->audio) : null,
                    ]
                ],
                'substituted_prizes' => [],
                'message' => 'Selamat! Anda mendapatkan hadiah'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log game result (optional)
     */
    private function logGameResult($gameId, $prizeId, $userId = null)
    {
        // Implementasi log ke database jika diperlukan
        // Contoh:
        // GameHistory::create([
        //     'game_id' => $gameId,
        //     'prize_id' => $prizeId,
        //     'user_id' => $userId ?? auth()->id(),
        //     'won_at' => now(),
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        return $this->showGlassGame($game);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
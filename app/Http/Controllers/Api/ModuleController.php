<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuleController extends Controller
{
    /**
     * Tampilkan semua module (public)
     */
    public function index()
    {
        try {
            $modules = Module::with(['levels'])->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Module berhasil diambil',
                'data' => $modules
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan detail module (public)
     */
    public function show($id)
    {
        try {
            $module = Module::with(['levels'])->find($id);

            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Module berhasil diambil',
                'data' => $module
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/modules/{id}/levels
     * Daftar level dalam satu modul (public).
     *
     * 🎓 LEARNING: Endpoint ini dipakai Flutter untuk menampilkan
     * daftar level (Level 1-5) setelah anak memilih modul.
     * Response menyertakan status progres anak jika anak_id diberikan via query param.
     */
    public function levels(Request $request, $id)
    {
        try {
            $module = Module::find($id);

            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module tidak ditemukan'
                ], 404);
            }

            $levels = $module->levels()->orderBy('order_number')->get();

            // 🎓 Jika anak_id dikirim, sertakan status progres tiap level
            $anakId = $request->query('anak_id');
            $user = $request->user('sanctum');
            $canShowProgress = $anakId && $user && Anak::where('id', $anakId)
                ->where('user_id', $user->id)
                ->exists();

            if ($canShowProgress) {
                $levels = $levels->map(function ($level) use ($anakId) {
                    $progress = $level->progresAnaks()
                        ->where('anak_id', $anakId)
                        ->first();

                    $level->progress = $progress ? [
                        'score'   => $progress->score,
                        'current_score' => $progress->current_score,
                        'bintang' => $progress->bintang,
                        'selesai' => $progress->selesai,
                        'current_item' => $progress->current_item,
                        'total_items' => $progress->total_items,
                        'mistakes' => $progress->mistakes,
                        'duration_seconds' => $progress->duration_seconds,
                        'last_played_at' => $progress->last_played_at,
                    ] : null;

                    return $level;
                });
            }

            return response()->json([
                'success' => true,
                'message' => "Levels untuk modul {$module->name}",
                'data'    => [
                    'module' => [
                        'id'   => $module->id,
                        'name' => $module->name,
                        'type' => $module->type,
                    ],
                    'levels' => $levels->values()->all(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/modules/{moduleId}/levels/{levelId}/items
     * Ambil soal/item dari 1 level berdasarkan tipe modul.
     *
     * 🎓 LEARNING: Endpoint ini adalah "jembatan" antara Flutter dan DB.
     * Satu level bisa punya tipe soal berbeda tergantung modul-nya:
     * - Modul 'berhitung' → tabel counting_items
     * - Modul 'menulis'   → tabel writing_items
     * - Modul 'membaca'   → tabel reading_items
     *
     * Pola ini disebut Polymorphic Content — 1 level, banyak tipe konten.
     */
    public function levelItems($moduleId, $levelId)
    {
        try {
            $module = Module::find($moduleId);
            if (!$module) {
                return response()->json(['success' => false, 'message' => 'Module tidak ditemukan'], 404);
            }

            // Pastikan level ini memang milik modul yang diminta
            $level = $module->levels()->where('id', $levelId)->first();
            if (!$level) {
                return response()->json(['success' => false, 'message' => 'Level tidak ditemukan dalam modul ini'], 404);
            }

            // Pilih tabel konten berdasarkan slug modul
            $items = match($module->slug) {
                'berhitung' => $level->countingItems()
                    ->where('is_active', true)
                    ->orderBy('order_number')
                    ->get()
                    ->map(fn($i) => [
                    'id'             => $i->id,
                    'nama_objek'     => $i->nama_objek,
                    'gambar_objek'   => $i->gambar_objek,
                    'jenis_operasi'  => $i->jenis_operasi,
                    'nilai_kiri'     => $i->nilai_kiri,
                    'nilai_kanan'    => $i->nilai_kanan,
                    'hasil'          => $i->hasil,
                    'order_number'   => $i->order_number,
                ]),
                'menulis' => $level->writingItems()
                    ->where('is_active', true)
                    ->orderBy('order_number')
                    ->get()
                    ->map(fn($i) => [
                    'id'         => $i->id,
                    'text'       => $i->text,
                    'image_path' => $i->image_path,
                    'type'       => $i->type,
                    'order_number' => $i->order_number,
                ]),
                'membaca' => $level->readingItems()
                    ->where('is_active', true)
                    ->orderBy('order_number')
                    ->get()
                    ->map(fn($i) => [
                        'id'            => $i->id,
                        'title'         => $i->prompt ?: "Lengkapi {$i->word}",
                        'prompt'        => $i->prompt,
                        'text'          => $i->word,
                        'word'          => $i->word,
                        'image'         => $i->image,
                        'answer'        => $i->answer,
                        'options'       => $i->options ?: [],
                        'missing_index' => $i->missing_index,
                        'order_number'  => $i->order_number,
                    ]),
                default => collect([]),
            };

            return response()->json([
                'success' => true,
                'message' => "Soal untuk {$module->name} - {$level->title}",
                'data'    => [
                    'module_slug' => $module->slug,
                    'level'       => [
                        'id'    => $level->id,
                        'title' => $level->title,
                        'order' => $level->order_number,
                    ],
                    'items' => $items,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Simpan module baru (protected - admin only)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'slug' => 'required|string|unique:modules,slug',
                'foto' => 'nullable|string'
            ]);

            $module = Module::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Module berhasil dibuat',
                'data' => $module
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update module (protected - admin only)
     */
    public function update(Request $request, $id)
    {
        try {
            $module = Module::find($id);

            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'type' => 'sometimes|string',
                'slug' => ['sometimes', 'string', Rule::unique('modules', 'slug')->ignore($id)],
                'foto' => 'nullable|string'
            ]);

            $module->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Module berhasil diupdate',
                'data' => $module
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus module (protected - admin only)
     */
    public function destroy($id)
    {
        try {
            $module = Module::find($id);

            if (!$module) {
                return response()->json([
                    'success' => false,
                    'message' => 'Module tidak ditemukan'
                ], 404);
            }

            $module->delete();

            return response()->json([
                'success' => true,
                'message' => 'Module berhasil dihapus',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

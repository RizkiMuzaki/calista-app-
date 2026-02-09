<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            $modules = Module::with(['levels', 'books'])->get();
            
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
            $module = Module::with(['levels', 'books'])->find($id);

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

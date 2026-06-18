<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ElevenLabsTtsService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AudioPackController extends Controller
{
    public function getManifest()
    {
        try {
            $manifestPath = "audio_packs/shared_pack_manifest.json";
            if (Storage::disk('public')->exists($manifestPath)) {
                $manifest = json_decode(Storage::disk('public')->get($manifestPath), true);
                return response()->json([
                    'status' => 'success',
                    'data' => $manifest
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Shared audio pack manifest not found. Please run php artisan audio:generate-shared-pack first.'
            ], 404);
        } catch (\Exception $e) {
            Log::error("Failed to read audio pack manifest: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getNameAudio(Request $request, ElevenLabsTtsService $tts)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50'
            ]);

            $name = trim($request->query('name'));
            // Normalize name for filename
            $safeName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name));

            if (empty($safeName)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid name format'
                ], 400);
            }

            $fileName = "names/{$safeName}.mp3";
            $publicPath = "audio_packs/{$fileName}";

            if (!Storage::disk('public')->exists($publicPath)) {
                // Generate name via ElevenLabs
                $result = $tts->synthesize($name, $request->user(), 'audio_pack_generation');

                if (!$result['success']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal menghasilkan suara nama: ' . ($result['reason'] ?? 'unknown error')
                    ], 500);
                }

                Storage::disk('public')->makeDirectory('audio_packs/names');
                Storage::disk('public')->put($publicPath, $result['audio']);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'name' => $name,
                    'url' => asset("storage/audio_packs/{$fileName}"),
                    'size' => Storage::disk('public')->size($publicPath)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to generate/get name audio: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghasilkan suara nama: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TypecastService
{
    protected $pythonApiUrl;

    public function __construct()
    {
        // URL Python Flask API - sesuaikan dengan environment Anda
        $this->pythonApiUrl = env('PYTHON_API_URL', 'http://localhost:5000');
    }

    /**
     * Generate greeting audio untuk user
     */
    public function generateGreetingAudio($userName, $moduleName, $levelName, $additionalText = '')
    {
        try {
            $response = Http::post("{$this->pythonApiUrl}/api/laravel-greeting", [
                'user_name' => $userName,
                'module_name' => $moduleName,
                'level_name' => $levelName,
                'additional_text' => $additionalText
            ]);

            if ($response->successful()) {
                // Simpan audio ke storage sementara
                $audioContent = $response->body();
                $fileName = 'greeting_' . time() . '.mp3';
                $path = storage_path('app/public/tts/' . $fileName);
                
                // Buat direktori jika belum ada
                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }
                
                file_put_contents($path, $audioContent);
                
                return [
                    'success' => true,
                    'path' => 'storage/tts/' . $fileName,
                    'url' => asset('storage/tts/' . $fileName)
                ];
            } else {
                Log::error('Typecast API error: ' . $response->body());
                return [
                    'success' => false,
                    'error' => 'Failed to generate audio'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Typecast service error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate audio dari teks custom
     */
    public function generateAudio($text, $options = [])
    {
        try {
            $payload = array_merge(['text' => $text], $options);
            
            $response = Http::post("{$this->pythonApiUrl}/api/typecast/generate", $payload);

            if ($response->successful()) {
                $audioContent = $response->body();
                $fileName = 'audio_' . time() . '.mp3';
                $path = storage_path('app/public/tts/' . $fileName);
                
                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }
                
                file_put_contents($path, $audioContent);
                
                return [
                    'success' => true,
                    'path' => 'storage/tts/' . $fileName,
                    'url' => asset('storage/tts/' . $fileName)
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'API Error: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test koneksi ke Python API
     */
    public function testConnection()
    {
        try {
            $response = Http::get("{$this->pythonApiUrl}/api/typecast/test");
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connected successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Connection failed: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate TTS audio for arbitrary text.
     *
     * Returns array: ['success' => bool, 'url' => string|null, 'message' => string|null]
     */
    public function generateTTS(string $text): array
    {
        try {
            // Reuse existing greeting generator if available (keeps implementation simple)
            if (method_exists($this, 'generateGreetingAudio')) {
                $res = $this->generateGreetingAudio('Teman', 'TTS', 'Text to Speech', $text);
                return [
                    'success' => $res['success'] ?? false,
                    'url' => $res['url'] ?? null,
                    'message' => $res['message'] ?? null
                ];
            }

            // Fallback: not implemented in this environment
            return [
                'success' => false,
                'url' => null,
                'message' => 'TTS generation not implemented'
            ];
        } catch (\Exception $e) {
            Log::error('TypecastService::generateTTS error: ' . $e->getMessage());
            return [
                'success' => false,
                'url' => null,
                'message' => $e->getMessage()
            ];
        }
    }
}
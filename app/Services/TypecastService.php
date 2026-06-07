<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Backward-compatible adapter for old controllers.
 *
 * The previous Typecast/Python implementation has been removed from this
 * service. Calls now go through the single Calista voice provider:
 * ElevenLabs Nusa TTS with backend credit tracking.
 */
class TypecastService
{
    private ElevenLabsTtsService $tts;

    public function __construct()
    {
        $this->tts = app(ElevenLabsTtsService::class);
    }

    public function generateGreetingAudio($userName, $moduleName, $levelName, $additionalText = '')
    {
        $text = trim("Halo {$userName}! Aku Nusa. Ayo belajar {$moduleName} bagian {$levelName}. {$additionalText}");
        return $this->generateAudio($text, ['feature' => 'legacy_greeting']);
    }

    public function generateAudio($text, $options = [])
    {
        try {
            $result = $this->tts->synthesize(
                (string) $text,
                auth()->user(),
                (string) ($options['feature'] ?? 'legacy_tts')
            );

            if (!$result['success']) {
                return [
                    'success' => false,
                    'error' => $result['message'] ?? 'Kuota suara Nusa habis.',
                    'credit' => $result['credit'] ?? null,
                ];
            }

            $fileName = 'tts/nusa_' . now()->format('Ymd_His_u') . '.mp3';
            Storage::disk('public')->put($fileName, $result['audio']);

            return [
                'success' => true,
                'path' => 'storage/' . $fileName,
                'url' => asset('storage/' . $fileName),
                'credit' => $result['credit'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Nusa TTS adapter failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function testConnection()
    {
        return [
            'success' => filled(config('services.elevenlabs.api_key')) && filled(config('services.elevenlabs.nusa_voice_id')),
            'message' => 'ElevenLabs Nusa TTS adapter',
            'model' => config('services.elevenlabs.model_id'),
        ];
    }

    public function generateTTS(string $text): array
    {
        $res = $this->generateAudio($text, ['feature' => 'legacy_direct_tts']);

        return [
            'success' => $res['success'] ?? false,
            'url' => $res['url'] ?? null,
            'message' => $res['error'] ?? null,
        ];
    }
}

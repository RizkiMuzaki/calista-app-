<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ElevenLabsTtsService
{
    public function __construct(private AiCreditService $credits)
    {
    }

    public function synthesize(string $text, ?User $user = null, string $feature = 'nusa_tts'): array
    {
        $cleanText = $this->normalizeText($text);
        if ($cleanText === '') {
            throw new RuntimeException('Teks TTS kosong.');
        }

        $requested = $this->credits->estimateElevenLabsCredits($cleanText);
        $guard = $this->credits->canSpend($user, $requested, $feature);
        if (!$guard['allowed']) {
            return [
                'success' => false,
                'reason' => 'credit_exhausted',
                'message' => 'Kuota suara Nusa hari ini sudah habis. Coba lagi nanti ya.',
                'credit' => $guard,
            ];
        }

        $apiKey = config('services.elevenlabs.api_key');
        $voiceId = config('services.elevenlabs.nusa_voice_id');
        if (!$apiKey || !$voiceId) {
            throw new RuntimeException('ELEVENLABS_API_KEY atau ELEVENLABS_NUSA_VOICE_ID belum diatur.');
        }

        $baseUrl = rtrim(config('services.elevenlabs.base_url'), '/');
        $outputFormat = config('services.elevenlabs.output_format');
        $latency = (int) config('services.elevenlabs.optimize_streaming_latency', 3);
        $url = "{$baseUrl}/v1/text-to-speech/{$voiceId}/stream";

        $response = Http::withHeaders([
                'xi-api-key' => $apiKey,
                'Accept' => 'audio/mpeg',
            ])
            ->timeout(45)
            ->post($url . '?' . http_build_query([
                'output_format' => $outputFormat,
                'optimize_streaming_latency' => $latency,
            ]), [
                'text' => $cleanText,
                'model_id' => config('services.elevenlabs.model_id'),
                'language_code' => 'id',
                'voice_settings' => [
                    'stability' => (float) config('services.elevenlabs.voice_settings.stability'),
                    'similarity_boost' => (float) config('services.elevenlabs.voice_settings.similarity_boost'),
                    'style' => (float) config('services.elevenlabs.voice_settings.style'),
                    'speed' => (float) config('services.elevenlabs.voice_settings.speed'),
                    'use_speaker_boost' => (bool) config('services.elevenlabs.voice_settings.use_speaker_boost'),
                ],
            ]);

        if (!$response->successful() || !str_contains((string) $response->header('Content-Type'), 'audio')) {
            Log::warning('ElevenLabs TTS failed', [
                'status' => $response->status(),
                'content_type' => $response->header('Content-Type'),
                'body' => substr($response->body(), 0, 300),
            ]);
            throw new RuntimeException('ElevenLabs TTS gagal.');
        }

        $spent = $this->credits->recordSpend($user, $feature, $cleanText, [
            'model' => config('services.elevenlabs.model_id'),
            'voice_id' => $voiceId,
            'output_format' => $outputFormat,
        ]);

        return [
            'success' => true,
            'audio' => $response->body(),
            'content_type' => $response->header('Content-Type') ?: 'audio/mpeg',
            'text' => $cleanText,
            'credit' => $spent,
        ];
    }

    private function normalizeText(string $text): string
    {
        $text = preg_replace('/\s+/u', ' ', trim($text)) ?? trim($text);
        return mb_substr($text, 0, 900, 'UTF-8');
    }
}

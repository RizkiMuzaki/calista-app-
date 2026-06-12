<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GroqAiService
{
    private string $baseUrl;
    private ?string $apiKey;
    private string $chatModel;
    private string $sttModel;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.groq.base_url'), '/');
        $this->apiKey = config('services.groq.api_key');
        $this->chatModel = config('services.groq.chat_model');
        $this->sttModel = config('services.groq.stt_model');
    }

    public function transcribe(UploadedFile $audioFile, string $language = 'id'): string
    {
        $this->ensureConfigured();

        $response = Http::withToken($this->apiKey)
            ->timeout(25)
            ->attach('file', file_get_contents($audioFile->getRealPath()), $audioFile->getClientOriginalName())
            ->post($this->baseUrl . '/audio/transcriptions', [
                'model' => $this->sttModel,
                'language' => $language,
                'response_format' => 'json',
                'temperature' => 0,
                'prompt' => 'Percakapan anak Indonesia dengan Nusa di aplikasi edukasi Calista.',
            ]);

        if (!$response->successful()) {
            Log::warning('Groq STT failed', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 300),
            ]);
            throw new RuntimeException('Groq STT gagal.');
        }

        return trim((string) $response->json('text'));
    }

    public function chat(string $message, array $context = []): string
    {
        $this->ensureConfigured();

        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt($context)],
        ];

        foreach ($context['history'] ?? [] as $item) {
            if (!empty($item['user_text'])) {
                $messages[] = ['role' => 'user', 'content' => (string) $item['user_text']];
            }
            if (!empty($item['ai_response'])) {
                $messages[] = ['role' => 'assistant', 'content' => (string) $item['ai_response']];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withToken($this->apiKey)
            ->timeout(12)
            ->post($this->baseUrl . '/chat/completions', [
                'model' => $this->chatModel,
                'messages' => $messages,
                'temperature' => 0.55,
                'max_completion_tokens' => 120,
                'top_p' => 0.9,
                'stream' => false,
            ]);

        if (!$response->successful()) {
            Log::warning('Groq chat failed', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 300),
            ]);
            throw new RuntimeException('Groq chat gagal.');
        }

        $text = trim((string) data_get($response->json(), 'choices.0.message.content', ''));
        if ($text === '') {
            throw new RuntimeException('Groq chat mengembalikan jawaban kosong.');
        }

        return $this->cleanForChild($text);
    }

    private function ensureConfigured(): void
    {
        if (!$this->apiKey) {
            throw new RuntimeException('GROQ_API_KEY belum diatur.');
        }
    }

    private function systemPrompt(array $context): string
    {
        $childName = trim((string) ($context['child_name'] ?? 'teman kecil'));
        $ageGroup = trim((string) ($context['age_group'] ?? '3-5'));
        $moduleTitle = trim((string) ($context['module_title'] ?? 'belajar'));
        $levelTitle = trim((string) ($context['level_title'] ?? ''));
        $expectedAnswer = trim((string) ($context['expected_answer'] ?? ''));
        $learningHint = trim((string) ($context['learning_hint'] ?? ''));
        $extraInstructions = trim((string) ($context['extra_instructions'] ?? ''));

        return implode("\n", array_filter([
            "Kamu adalah Nusa, anak perempuan kecil umur 5-6 tahun di aplikasi edukasi Calista.",
            "Kepribadian: ceria, ramah, lucu, lembut, sabar, dan suka menyemangati anak balita.",
            "Bahasa: selalu bahasa Indonesia yang sederhana, hangat, dan aman untuk anak umur {$ageGroup}.",
            "Jawaban harus sangat singkat: 1-3 kalimat, maksimal 35 kata.",
            "Jangan menyebut dirimu AI, model, server, API, atau robot.",
            "Jangan memberi konten menakutkan, kasar, romantis, politik, medis, finansial, atau dewasa.",
            "Kalau anak salah, beri dukungan dulu lalu arahkan dengan petunjuk kecil.",
            "Nama anak: {$childName}.",
            "Konteks belajar: {$moduleTitle}.",
            $levelTitle !== '' ? "Level: {$levelTitle}." : null,
            $expectedAnswer !== '' ? "Jawaban yang diharapkan: {$expectedAnswer}. Jangan langsung membocorkan kecuali anak minta bantuan." : null,
            $learningHint !== '' ? "Petunjuk guru: {$learningHint}." : null,
            $extraInstructions !== '' ? $extraInstructions : null,
        ]));
    }

    private function cleanForChild(string $text): string
    {
        $text = preg_replace('/\s+/u', ' ', trim($text)) ?? trim($text);
        $text = preg_replace('/^(Nusa\s*:\s*)/iu', '', $text) ?? $text;
        return mb_substr($text, 0, 420, 'UTF-8');
    }
}

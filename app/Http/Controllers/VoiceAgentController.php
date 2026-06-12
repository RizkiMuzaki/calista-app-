<?php

namespace App\Http\Controllers;

use App\Services\AiCreditService;
use App\Services\ElevenLabsTtsService;
use App\Services\GroqAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class VoiceAgentController extends Controller
{
    public function __construct(
        private GroqAiService $groq,
        private ElevenLabsTtsService $tts,
        private AiCreditService $credits,
    ) {
    }


    public function processVoice(Request $request)
    {
        $validated = $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,ogg,m4a,webm|max:5120',
            'age_group' => 'nullable|string|in:3-5,5-7,7-9,9-12',
            'child_name' => 'nullable|string|max:80',
            'module_slug' => 'nullable|string|max:80',
            'module_title' => 'nullable|string|max:120',
            'level_title' => 'nullable|string|max:120',
            'expected_answer' => 'nullable|string|max:120',
            'learning_instruction' => 'nullable|string|max:255',
            'learning_hint' => 'nullable|string|max:255',
            'session_id' => 'nullable|string|max:100',
        ]);

        try {
            $user = auth()->user();
            
            // 🆓 Blokir Akses Voice untuk User Free
            $plan = $this->credits->planFor($user);
            if ($plan['code'] === 'free') {
                return response()->json([
                    'success' => false,
                    'message' => 'Fitur Voice Tutor Nusa eksklusif untuk member Calista Plus. Yuk berlangganan!',
                ], 403);
            }

            $ageGroup = $validated['age_group'] ?? '3-5';
            $audioFile = $request->file('audio');
            $userText = $this->groq->transcribe($audioFile, 'id');

            if ($userText === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Nusa belum mendengar suaramu. Coba bicara pelan-pelan ya.',
                    'user_text' => '',
                ], 422);
            }

            $sessionId = $request->input('session_id', 'general');
            $context = $this->contextFromRequest($request, $ageGroup);
            $context['history'] = $this->historyFor($user?->id, $sessionId);

            // ⏱️ Pembatasan Maksimal 5 Pertanyaan per Sesi
            $turnKey = 'voice_session_turn_count_' . $sessionId;
            $turnCount = (int) Cache::get($turnKey, 0) + 1;
            Cache::put($turnKey, $turnCount, now()->addHours(2));

            $extraInstructions = [];
            if ($turnCount >= 5) {
                $extraInstructions[] = "Ini adalah giliran terakhir. Ucapkan kalimat perpisahan yang hangat dan katakan bahwa kamu (Nusa) harus tidur/istirahat sekarang. Jangan memberikan pertanyaan baru lagi.";
            }

            // 📈 Integrasi Entity Extraction & Penyimpanan Profil Anak
            $extraInstructions[] = "Ekstraksi Minat Anak: Jika anak menyebutkan cita-citanya (seperti dokter, astronot, tentara, dll), hobinya (seperti berenang, menggambar, bersepeda, dll), atau makanan kesukaannya (seperti sayur bening, fried chicken, dll), tambahkan tag berikut di akhir jawabanmu: <profile_entities>{\"cita_cita\": \"cita-cita yang terdeteksi atau null\", \"hobi\": \"hobi yang terdeteksi atau null\", \"makanan\": \"makanan kesukaan yang terdeteksi atau null\"}</profile_entities>. Jika tidak ada yang terdeteksi, jangan tambahkan tag tersebut.";

            $context['extra_instructions'] = implode("\n", $extraInstructions);

            $aiResponse = $this->groq->chat($userText, $context);

            // Ekstrak entitas jika tag terdeteksi
            $entities = null;
            if (preg_match('/<profile_entities>(.*?)<\/profile_entities>/is', $aiResponse, $matches)) {
                $entitiesJson = trim($matches[1]);
                $entities = json_decode($entitiesJson, true);
                $aiResponse = trim(str_replace($matches[0], '', $aiResponse));
            }

            $childId = $request->input('user_id');
            $activeAnak = null;
            if ($childId && $user) {
                $activeAnak = $user->anaks()->where('id', $childId)->first();
            }
            if (!$activeAnak && $user) {
                $activeAnak = $user->anaks()->where('is_active', true)->first();
            }

            if ($entities && $activeAnak) {
                if (!empty($entities['cita_cita']) && $entities['cita_cita'] !== 'null') {
                    $activeAnak->cita_cita = $entities['cita_cita'];
                }
                if (!empty($entities['hobi']) && $entities['hobi'] !== 'null') {
                    $activeAnak->hobi = $entities['hobi'];
                }
                if (!empty($entities['makanan']) && $entities['makanan'] !== 'null') {
                    $activeAnak->makanan_favorit = $entities['makanan'];
                }
                $activeAnak->save();
            }

            $this->trackVoiceChatSession($user, $activeAnak, $sessionId, $userText, $aiResponse, $turnCount);

            $audio = $this->tts->synthesize($aiResponse, $user, 'voice_chat');

            $this->saveToHistory($user?->id, [
                'user_text' => $userText,
                'ai_response' => $aiResponse,
                'age_group' => $ageGroup,
                'timestamp' => now()->toDateTimeString(),
            ], $sessionId);

            if (!$audio['success']) {
                return $this->creditFallback($aiResponse, $userText, $audio);
            }

            return response($audio['audio'])
                ->header('Content-Type', $audio['content_type'])
                ->header('Cache-Control', 'no-cache, no-store')
                ->header('X-STT-Text', $this->safeHeader($userText))
                ->header('X-AI-Response', $this->safeHeader($aiResponse))
                ->header('X-Age-Group', $ageGroup)
                ->header('X-AI-Plan', $audio['credit']['plan'] ?? 'free')
                ->header('X-AI-Credits-Remaining', (string) ($audio['credit']['remaining'] ?? 0))
                ->header('Access-Control-Expose-Headers', 'X-STT-Text,X-AI-Response,X-Age-Group,X-AI-Plan,X-AI-Credits-Remaining');
        } catch (\Throwable $e) {
            Log::error('Nusa voice pipeline failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Nusa sedang susah bicara. Coba lagi sebentar ya.',
                'diagnostic' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function assessReading(Request $request)
    {
        $validated = $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,ogg,m4a,webm|max:5120',
            'target_text' => 'required|string|max:80',
            'age_group' => 'nullable|string|in:3-5,5-7,7-9,9-12',
        ]);

        try {
            $heardText = $this->groq->transcribe($request->file('audio'), 'id');
            $targetText = (string) $validated['target_text'];
            $heard = $this->normalizeReadingText($heardText);
            $target = $this->normalizeReadingText($targetText);
            $isCorrect = $heard !== '' && $target !== '' && ($heard === $target || str_contains($heard, $target));

            return response()->json([
                'success' => true,
                'is_correct' => $isCorrect,
                'heard_text' => $heardText,
                'target_text' => $targetText,
                'score' => $isCorrect ? 100 : 0,
            ]);
        } catch (\Throwable $e) {
            Log::error('Reading assessment failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Nusa belum dapat memeriksa bacaanmu.',
            ], 500);
        }
    }

    public function textToSpeech(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:900',
            'age_group' => 'nullable|string|in:3-5,5-7,7-9,9-12',
            'feature' => 'nullable|string|max:48',
        ]);

        try {
            $user = auth()->user();

            // 🆓 Blokir Akses Voice untuk User Free
            $plan = $this->credits->planFor($user);
            if ($plan['code'] === 'free') {
                return response()->json([
                    'success' => false,
                    'message' => 'Fitur Voice Tutor Nusa eksklusif untuk member Calista Plus. Yuk berlangganan!',
                ], 403);
            }
            $audio = $this->tts->synthesize(
                $validated['text'],
                auth()->user(),
                $validated['feature'] ?? 'direct_tts'
            );

            if (!$audio['success']) {
                return $this->creditFallback($validated['text'], '', $audio);
            }

            return response($audio['audio'])
                ->header('Content-Type', $audio['content_type'])
                ->header('Cache-Control', 'public, max-age=31536000')
                ->header('X-Calista-TTS-Source', 'elevenlabs')
                ->header('X-AI-Plan', $audio['credit']['plan'] ?? 'free')
                ->header('X-AI-Credits-Remaining', (string) ($audio['credit']['remaining'] ?? 0))
                ->header('Access-Control-Expose-Headers', 'X-Calista-TTS-Source,X-AI-Plan,X-AI-Credits-Remaining');
        } catch (\Throwable $e) {
            Log::error('ElevenLabs direct TTS failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Suara Nusa belum siap. Coba lagi sebentar ya.',
                'diagnostic' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function speechToText(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,ogg,m4a,webm|max:5120',
        ]);

        try {
            $text = $this->groq->transcribe($request->file('audio'), 'id');

            return response()->json([
                'success' => true,
                'text' => $text,
                'engine' => 'groq-whisper',
            ]);
        } catch (\Throwable $e) {
            Log::error('Groq STT failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Nusa belum bisa mendengar suaramu.',
                'diagnostic' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function textChat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'age_group' => 'nullable|string|in:3-5,5-7,7-9,9-12',
            'child_name' => 'nullable|string|max:80',
            'module_title' => 'nullable|string|max:120',
            'level_title' => 'nullable|string|max:120',
        ]);

        try {
            $user = auth()->user();

            // 🆓 Blokir Akses Voice untuk User Free
            $plan = $this->credits->planFor($user);
            if ($plan['code'] === 'free') {
                return response()->json([
                    'success' => false,
                    'message' => 'Fitur Voice Tutor Nusa eksklusif untuk member Calista Plus. Yuk berlangganan!',
                ], 403);
            }
            $ageGroup = $validated['age_group'] ?? '3-5';
            $context = $this->contextFromRequest($request, $ageGroup);
            $context['history'] = $this->historyFor($user?->id);
            $aiResponse = $this->groq->chat($validated['message'], $context);
            $audio = $this->tts->synthesize($aiResponse, $user, 'text_chat');

            $this->saveToHistory($user?->id, [
                'user_text' => $validated['message'],
                'ai_response' => $aiResponse,
                'age_group' => $ageGroup,
                'timestamp' => now()->toDateTimeString(),
            ]);

            if (!$audio['success']) {
                return $this->creditFallback($aiResponse, $validated['message'], $audio);
            }

            return response($audio['audio'])
                ->header('Content-Type', $audio['content_type'])
                ->header('Cache-Control', 'no-cache, no-store')
                ->header('X-AI-Response', $this->safeHeader($aiResponse))
                ->header('X-AI-Plan', $audio['credit']['plan'] ?? 'free')
                ->header('X-AI-Credits-Remaining', (string) ($audio['credit']['remaining'] ?? 0))
                ->header('Access-Control-Expose-Headers', 'X-AI-Response,X-AI-Plan,X-AI-Credits-Remaining');
        } catch (\Throwable $e) {
            Log::error('Nusa text chat failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Nusa belum bisa menjawab sekarang.',
                'diagnostic' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function checkServerHealth()
    {
        return response()->json([
            'success' => true,
            'status' => 'online',
            'pipeline' => 'Groq STT -> Groq LLM -> ElevenLabs Streaming TTS',
            'tts_model' => config('services.elevenlabs.model_id'),
            'stt_model' => config('services.groq.stt_model'),
            'chat_model' => config('services.groq.chat_model'),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    public function creditStatus()
    {
        $guard = $this->credits->canSpend(auth()->user(), 1);

        return response()->json([
            'success' => true,
            'plan' => $guard['plan'],
            'limit' => $guard['limit'],
            'used' => $guard['used'],
            'remaining' => $guard['remaining'],
            'period_start' => $guard['period_start'],
        ]);
    }

    public function getHistory(Request $request)
    {
        $history = $this->historyFor(auth()->id(), $request->session_id ?? 'general');

        return response()->json([
            'success' => true,
            'history' => $history,
            'count' => count($history),
        ]);
    }

    public function clearHistory(Request $request)
    {
        Cache::forget($this->historyKey(auth()->id(), $request->session_id ?? 'general'));

        return response()->json([
            'success' => true,
            'message' => 'Riwayat percakapan telah dihapus',
        ]);
    }

    public function learningSession(Request $request)
    {
        $topic = trim((string) $request->input('topic', 'belajar'));
        $text = "Halo! Aku Nusa. Ayo kita belajar {$topic} bersama-sama!";

        $request->merge(['text' => $text, 'feature' => 'learning_session']);
        return $this->textToSpeech($request);
    }

    public function testAudio(Request $request)
    {
        $request->merge([
            'text' => $request->input('text', 'Halo! Aku Nusa. Ayo belajar sambil bermain!'),
            'feature' => 'test_audio',
        ]);

        return $this->textToSpeech($request);
    }

    private function contextFromRequest(Request $request, string $ageGroup): array
    {
        return [
            'age_group' => $ageGroup,
            'child_name' => $request->input('child_name', 'teman kecil'),
            'module_slug' => $request->input('module_slug'),
            'module_title' => $request->input('module_title', 'belajar'),
            'level_title' => $request->input('level_title'),
            'expected_answer' => $request->input('expected_answer'),
            'learning_instruction' => $request->input('learning_instruction'),
            'learning_hint' => $request->input('learning_hint'),
        ];
    }

    private function normalizeReadingText(string $text): string
    {
        $uppercase = mb_strtoupper(trim($text), 'UTF-8');
        return preg_replace('/[^\p{L}\p{N}]+/u', '', $uppercase) ?? '';
    }

    private function saveToHistory($userId, array $data, string $sessionId = 'general'): void
    {
        $history = $this->historyFor($userId, $sessionId);
        $history[] = $data;

        Cache::put($this->historyKey($userId, $sessionId), array_slice($history, -12), now()->addDays(7));
    }

    private function historyFor($userId, string $sessionId = 'general'): array
    {
        return Cache::get($this->historyKey($userId, $sessionId), []);
    }

    private function historyKey($userId, string $sessionId): string
    {
        return 'voice_history_' . ($userId ?? 'guest') . '_' . $sessionId;
    }

    private function safeHeader(string $value): string
    {
        return str_replace(["\r", "\n"], ' ', mb_substr($value, 0, 180, 'UTF-8'));
    }

    private function trackVoiceChatSession($user, $child, string $sessionId, string $userText, string $aiResponse, int $turnCount): void
    {
        if (!$user || !$child) {
            return;
        }

        try {
            $playSession = \App\Models\PlaySession::firstOrNew([
                'session_uuid' => $sessionId,
            ]);

            // Accumulate duration: 30 seconds per turn
            $duration = $playSession->exists ? ($playSession->duration_seconds + 30) : 30;
            $endedAt = now();
            $startedAt = $playSession->exists ? $playSession->started_at : $endedAt->copy()->subSeconds(30);

            $metadata = (array) ($playSession->metadata ?? []);
            $questions = $metadata['questions'] ?? [];
            $questions[] = [
                'question' => $userText,
                'answer' => $aiResponse,
                'timestamp' => now()->toDateTimeString(),
            ];

            $playSession->fill([
                'user_id' => $user->id,
                'anak_id' => $child->id,
                'source' => 'voice_chat',
                'module_slug' => 'ai_chat',
                'module_name' => 'Tanya Nusa',
                'level_title' => 'Tanya Nusa (Whisper)',
                'status' => 'completed',
                'score' => 100,
                'current_score' => 100,
                'bintang' => 5,
                'duration_seconds' => $duration,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'played_on' => $endedAt->toDateString(),
                'metadata' => array_merge($metadata, [
                    'turns' => $turnCount,
                    'questions' => $questions,
                ]),
            ]);
            $playSession->save();
        } catch (\Throwable $e) {
            Log::error('Failed to track voice chat session', ['error' => $e->getMessage()]);
        }
    }

    private function creditFallback(string $aiText, string $userText, array $audio)
    {
        return response()->json([
            'success' => false,
            'fallback' => true,
            'reason' => $audio['reason'] ?? 'tts_unavailable',
            'message' => $audio['message'] ?? 'Suara Nusa belum tersedia.',
            'text' => $aiText,
            'ai_response' => $aiText,
            'user_text' => $userText,
            'credit' => $audio['credit'] ?? null,
        ], 402)->header('X-AI-Response', $this->safeHeader($aiText))
            ->header('X-AI-Credit-Exhausted', 'true')
            ->header('Access-Control-Expose-Headers', 'X-AI-Response,X-AI-Credit-Exhausted');
    }
}

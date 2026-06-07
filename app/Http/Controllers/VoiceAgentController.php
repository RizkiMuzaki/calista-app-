<?php

namespace App\Http\Controllers;

use App\Models\Module;
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

    public function index(Request $request)
    {
        $user = auth()->user();
        $activeAnak = $user?->anaks()->where('is_active', true)->first();

        $modules = Module::where('type', 'voice_chat')
            ->orWhere('slug', 'LIKE', 'voice-%')
            ->orderBy('order_number')
            ->get();

        return view('pages.voice-agent.index', compact('modules', 'activeAnak'));
    }

    public function voiceChat(Request $request, $slug = null)
    {
        $user = auth()->user();
        $activeAnak = $user?->anaks()->where('is_active', true)->first();
        $module = $slug ? Module::where('slug', $slug)->first() : null;
        $sessionId = 'voice_' . ($slug ?? 'general') . '_' . ($user?->id ?? 'guest') . '_' . now()->timestamp;

        session(['voice_session_id' => $sessionId]);

        return view('pages.voice-agent.chat', compact('module', 'activeAnak', 'sessionId'));
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
        ]);

        try {
            $user = auth()->user();
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

            $context = $this->contextFromRequest($request, $ageGroup);
            $context['history'] = $this->historyFor($user?->id);
            $aiResponse = $this->groq->chat($userText, $context);
            $audio = $this->tts->synthesize($aiResponse, $user, 'voice_chat');

            $this->saveToHistory($user?->id, [
                'user_text' => $userText,
                'ai_response' => $aiResponse,
                'age_group' => $ageGroup,
                'timestamp' => now()->toDateTimeString(),
            ]);

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

    private function saveToHistory($userId, array $data): void
    {
        $sessionId = session('voice_session_id', 'general');
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

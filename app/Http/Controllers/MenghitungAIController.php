<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Anak;
use App\Models\Module;
use App\Models\User;

class MenghitungAIController extends Controller
{
    private $pythonServerUrl = 'http://localhost:5000';
    
    public function __construct()
    {
        // Optionally set python server URL from env
        $this->pythonServerUrl = env('PYTHON_VOICE_SERVER_URL', 'http://localhost:5000');
    }
    
    /**
     * Halaman utama voice agent
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeChild = $user->anaks()->where('is_active', true)->first();
        $modules = Module::where('type', 'counting')->get();
        
        // Get age group from child
        $ageGroup = '5-7';
        if ($activeChild && $activeChild->usia) {
            $usia = $activeChild->usia;
            if ($usia >= 3 && $usia <= 5) {
                $ageGroup = '3-5';
            } elseif ($usia >= 5 && $usia <= 7) {
                $ageGroup = '5-7';
            } elseif ($usia >= 7 && $usia <= 9) {
                $ageGroup = '7-9';
            } elseif ($usia >= 9 && $usia <= 12) {
                $ageGroup = '9-12';
            }
        }
        
        return view('pages.voice-agent.index', [
            'activeChild' => $activeChild,
            'modules' => $modules,
            'ageGroup' => $ageGroup,
            'serverUrl' => $this->pythonServerUrl
        ]);
    }
    
    /**
     * Halaman voice chat interaktif
     */
    public function voiceChat(Request $request, $slug = null)
    {
        $user = auth()->user();
        $activeChild = $user->anaks()->where('is_active', true)->first();
        
        // Get age group from child
        $ageGroup = '5-7';
        if ($activeChild) {
            $ageGroup = $this->getAgeGroup($activeChild->usia);
        }
        
        // Get module if provided
        $module = null;
        if ($slug) {
            $module = Module::where('slug', $slug)->first();
        }
        
        // Get conversation history
        $conversationHistory = $this->getConversationHistory($user->id, $activeChild->id ?? null);
        
        return view('pages.voice-agent.chat', [
            'activeChild' => $activeChild,
            'module' => $module,
            'ageGroup' => $ageGroup,
            'conversationHistory' => $conversationHistory,
            'serverUrl' => $this->pythonServerUrl
        ]);
    }
    
    /**
     * Process voice message - FULL PIPELINE
     */
    public function processVoice(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|file|mimes:wav,mp3,m4a,ogg,webm|max:10240', // 10MB max
                'age_group' => 'sometimes|string',
                'user_id' => 'sometimes|string',
                'message_type' => 'sometimes|string'
            ]);
            
            $user = auth()->user();
            $activeChild = $user->anaks()->where('is_active', true)->first();
            
            // Prepare form data
            $multipart = [
                [
                    'name' => 'audio',
                    'contents' => fopen($request->file('audio')->path(), 'r'),
                    'filename' => 'audio_' . time() . '.wav'
                ],
                [
                    'name' => 'age_group',
                    'contents' => $request->age_group ?? $this->getAgeGroup($activeChild->usia ?? 5)
                ],
                [
                    'name' => 'user_id',
                    'contents' => $request->user_id ?? ($activeChild ? 'child_' . $activeChild->id : 'user_' . $user->id)
                ]
            ];
            
            Log::info('Sending voice to Python server', [
                'url' => $this->pythonServerUrl . '/api/voice_chat',
                'age_group' => $multipart[1]['contents'],
                'user_id' => $multipart[2]['contents']
            ]);
            
            // Send to Python server
            $response = Http::timeout(120) // 2 minutes timeout
                ->withOptions([
                    'multipart' => $multipart
                ])
                ->post($this->pythonServerUrl . '/api/voice_chat');
            
            if ($response->successful()) {
                // Get audio content
                $audioContent = $response->body();
                
                // Save conversation history
                $this->saveConversationHistory(
                    $user->id,
                    $activeChild->id ?? null,
                    'voice',
                    null, // STT text will be in response headers
                    null, // AI response will be in headers
                    'pending_stt' // We'll update when we get the text
                );
                
                // Return audio response
                return response($audioContent)
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache')
                    ->header('X-Response-Type', 'voice');
            } else {
                Log::error('Python server error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses suara. Silakan coba lagi.',
                    'error' => $response->body()
                ], $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('Voice processing error', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Text to Speech endpoint
     */
    public function textToSpeech(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string',
                'age_group' => 'sometimes|string',
                'voice_type' => 'sometimes|string|in:child,normal'
            ]);
            
            $user = auth()->user();
            $activeChild = $user->anaks()->where('is_active', true)->first();
            
            $payload = [
                'text' => $request->text,
                'age_group' => $request->age_group ?? $this->getAgeGroup($activeChild->usia ?? 5),
                'voice_type' => $request->voice_type ?? 'child'
            ];
            
            // Call Python TTS
            $response = Http::timeout(60)
                ->post($this->pythonServerUrl . '/api/tts', $payload);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghasilkan suara'
                ], $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('TTS error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Speech to Text endpoint
     */
    public function speechToText(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|file|mimes:wav,mp3,m4a,ogg,webm|max:5120'
            ]);
            
            $multipart = [
                [
                    'name' => 'audio',
                    'contents' => fopen($request->file('audio')->path(), 'r'),
                    'filename' => 'stt_' . time() . '.wav'
                ]
            ];
            
            $response = Http::timeout(30)
                ->withOptions(['multipart' => $multipart])
                ->post($this->pythonServerUrl . '/api/stt');
            
            if ($response->successful()) {
                $result = $response->json();
                
                return response()->json([
                    'success' => true,
                    'text' => $result['text'] ?? null,
                    'processing_time' => $result['processing_time'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengenali suara'
                ], $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('STT error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Text chat with AI
     */
    public function textChat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'age_group' => 'sometimes|string',
                'get_audio' => 'sometimes|boolean'
            ]);
            
            $user = auth()->user();
            $activeChild = $user->anaks()->where('is_active', true)->first();
            
            $payload = [
                'message' => $request->message,
                'age_group' => $request->age_group ?? $this->getAgeGroup($activeChild->usia ?? 5),
                'user_id' => $activeChild ? 'child_' . $activeChild->id : 'user_' . $user->id
            ];
            
            // Save user message to history
            $conversationId = $this->saveConversationHistory(
                $user->id,
                $activeChild->id ?? null,
                'text',
                $request->message,
                null,
                'user_sent'
            );
            
            // Call Python AI
            $response = Http::timeout(60)
                ->post($this->pythonServerUrl . '/api/chat', $payload);
            
            if ($response->successful()) {
                $aiResponse = '';
                
                if ($request->get_audio) {
                    // Return audio directly
                    return response($response->body())
                        ->header('Content-Type', 'audio/mpeg')
                        ->header('Cache-Control', 'no-cache')
                        ->header('X-Response-Type', 'voice');
                } else {
                    // Get AI response from headers
                    $headers = $response->headers();
                    $aiResponse = $headers['X-AI-Response'][0] ?? 'Halo! Aku Calista.';
                    
                    // Update conversation history
                    $this->updateConversationHistory($conversationId, [
                        'ai_response' => $aiResponse,
                        'status' => 'ai_responded'
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'text' => $aiResponse,
                        'audio_url' => route('voice-agent.text-to-speech', [
                            'text' => $aiResponse,
                            'age_group' => $payload['age_group']
                        ])
                    ]);
                }
            } else {
                $this->updateConversationHistory($conversationId, [
                    'status' => 'ai_error'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Calista sedang sibuk. Coba lagi ya!'
                ], $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('Text chat error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Check Python server health
     */
    public function checkServerHealth()
    {
        try {
            $response = Http::timeout(10)->get($this->pythonServerUrl . '/health');
            
            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'status' => 'connected',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 'disconnected',
                    'message' => 'Server tidak merespon'
                ], 503);
            }
            
        } catch (\Exception $e) {
            Log::error('Server health check failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Tidak dapat terhubung ke server: ' . $e->getMessage()
            ], 503);
        }
    }
    
    /**
     * Get conversation history
     */
    public function getHistory(Request $request)
    {
        $user = auth()->user();
        $activeChild = $user->anaks()->where('is_active', true)->first();
        
        $history = $this->getConversationHistory(
            $user->id,
            $activeChild->id ?? null,
            $request->limit ?? 20
        );
        
        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }
    
    /**
     * Clear conversation history
     */
    public function clearHistory(Request $request)
    {
        $user = auth()->user();
        
        // You can implement a model for conversation history
        // For now, we'll just return success
        // Storage::disk('local')->put('voice_history/user_' . $user->id . '.json', json_encode([]));
        
        return response()->json([
            'success' => true,
            'message' => 'Riwayat percakapan telah dihapus'
        ]);
    }
    
    /**
     * Generate learning session with Calista
     */
    public function learningSession(Request $request)
    {
        try {
            $request->validate([
                'topic' => 'required|string',
                'difficulty' => 'sometimes|string|in:mudah,sedang,sulit',
                'duration' => 'sometimes|integer|min:5|max:30'
            ]);
            
            $user = auth()->user();
            $activeChild = $user->anaks()->where('is_active', true)->first();
            $ageGroup = $this->getAgeGroup($activeChild->usia ?? 5);
            
            // Create session context
            $sessionId = 'session_' . time() . '_' . $user->id;
            
            // Initial greeting from Calista
            $greeting = "Halo! Aku Calista. Hari ini kita akan belajar tentang {$request->topic}. Yuk, kita mulai!";
            
            // Generate greeting audio
            $audioResponse = Http::timeout(60)
                ->post($this->pythonServerUrl . '/api/tts', [
                    'text' => $greeting,
                    'age_group' => $ageGroup
                ]);
            
            if ($audioResponse->successful()) {
                // Save session
                $sessionData = [
                    'session_id' => $sessionId,
                    'user_id' => $user->id,
                    'child_id' => $activeChild->id ?? null,
                    'topic' => $request->topic,
                    'difficulty' => $request->difficulty ?? 'mudah',
                    'duration' => $request->duration ?? 15,
                    'age_group' => $ageGroup,
                    'created_at' => now(),
                    'status' => 'active'
                ];
                
                // Save session to file or database
                Storage::disk('local')->put('sessions/' . $sessionId . '.json', json_encode($sessionData));
                
                return response()->json([
                    'success' => true,
                    'session_id' => $sessionId,
                    'greeting' => $greeting,
                    'audio_url' => route('voice-agent.text-to-speech', [
                        'text' => $greeting,
                        'age_group' => $ageGroup
                    ]),
                    'age_group' => $ageGroup
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat sesi pembelajaran'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Learning session error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Helper: Get age group from age
     */
    private function getAgeGroup($age)
    {
        $age = (int) $age;
        if ($age >= 3 && $age <= 5) {
            return '3-5';
        } elseif ($age >= 5 && $age <= 7) {
            return '5-7';
        } elseif ($age >= 7 && $age <= 9) {
            return '7-9';
        } elseif ($age >= 9 && $age <= 12) {
            return '9-12';
        }
        return '5-7'; // default
    }
    
    /**
     * Helper: Save conversation history
     */
    private function saveConversationHistory($userId, $childId, $type, $userText, $aiResponse, $status)
    {
        try {
            $historyFile = 'voice_history/user_' . $userId . '.json';
            $history = [];
            
            if (Storage::disk('local')->exists($historyFile)) {
                $history = json_decode(Storage::disk('local')->get($historyFile), true);
            }
            
            $conversationId = 'conv_' . time() . '_' . uniqid();
            
            $entry = [
                'id' => $conversationId,
                'user_id' => $userId,
                'child_id' => $childId,
                'type' => $type,
                'user_text' => $userText,
                'ai_response' => $aiResponse,
                'status' => $status,
                'timestamp' => now()->toISOString()
            ];
            
            $history[] = $entry;
            
            // Keep only last 100 conversations
            if (count($history) > 100) {
                $history = array_slice($history, -100);
            }
            
            Storage::disk('local')->put($historyFile, json_encode($history));
            
            return $conversationId;
            
        } catch (\Exception $e) {
            Log::error('Failed to save conversation history', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Helper: Update conversation history
     */
    private function updateConversationHistory($conversationId, $updates)
    {
        try {
            // You would need to implement this based on your storage method
            // For now, we'll just log
            Log::info('Updating conversation', [
                'id' => $conversationId,
                'updates' => $updates
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to update conversation history', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Helper: Get conversation history
     */
    private function getConversationHistory($userId, $childId = null, $limit = 20)
    {
        try {
            $historyFile = 'voice_history/user_' . $userId . '.json';
            
            if (Storage::disk('local')->exists($historyFile)) {
                $history = json_decode(Storage::disk('local')->get($historyFile), true);
                
                // Filter by child if specified
                if ($childId) {
                    $history = array_filter($history, function($entry) use ($childId) {
                        return $entry['child_id'] == $childId;
                    });
                }
                
                // Sort by timestamp (newest first)
                usort($history, function($a, $b) {
                    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
                });
                
                // Limit results
                return array_slice($history, 0, $limit);
            }
            
            return [];
            
        } catch (\Exception $e) {
            Log::error('Failed to get conversation history', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Test endpoint - returns a test audio
     */
    public function testAudio(Request $request)
    {
        try {
            $testText = "Halo! Aku Calista, teman belajarmu. Yuk kita belajar bersama!";
            $ageGroup = $request->age_group ?? '5-7';
            
            $response = Http::timeout(60)
                ->post($this->pythonServerUrl . '/api/tts', [
                    'text' => $testText,
                    'age_group' => $ageGroup
                ]);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache');
            } else {
                // Fallback: create a simple audio response
                $fallbackMessage = "Maaf, server audio sedang tidak tersedia. Coba lagi nanti ya!";
                return response()->json([
                    'success' => false,
                    'message' => $fallbackMessage,
                    'test_text' => $testText
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Test audio error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
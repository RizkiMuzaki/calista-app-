<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Module;
use App\Models\User;
use App\Models\Anak;

class VoiceAgentController extends Controller
{
    private $pythonBaseUrl = 'http://76.13.21.74:5003';
    
    /**
     * Halaman utama voice agent
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeAnak = $user->anaks()->where('is_active', true)->first();
        
        $modules = Module::where('type', 'voice_chat')
            ->orWhere('slug', 'LIKE', 'voice-%')
            ->orderBy('order_number')
            ->get();
        
        return view('pages.voice-agent.index', compact('modules', 'activeAnak'));
    }
    
    /**
     * Halaman voice chat interaktif
     */
    public function voiceChat(Request $request, $slug = null)
    {
        $user = auth()->user();
        $activeAnak = $user->anaks()->where('is_active', true)->first();
        
        $module = null;
        if ($slug) {
            $module = Module::where('slug', $slug)->first();
        }
        
        // Generate session ID
        $sessionId = 'voice_' . ($slug ?? 'general') . '_' . $user->id . '_' . now()->timestamp;
        session(['voice_session_id' => $sessionId]);
        
        return view('pages.voice-agent.chat', compact('module', 'activeAnak', 'sessionId'));
    }
    
    /**
     * Proses audio melalui full pipeline - DIPERBAIKI
     */
    public function processVoice(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|file|mimes:wav,mp3,ogg,m4a,webm|max:5120',
                'age_group' => 'nullable|string|in:3-5,5-7,7-9,9-12',
                'user_id' => 'nullable|string'
            ]);
            
            $audioFile = $request->file('audio');
            $ageGroup = $request->age_group ?? '5-7';
            $userId = $request->user_id ?? 'user_' . auth()->id();
            
            // Simpan file sementara untuk debugging
            $tempPath = storage_path('app/temp/' . uniqid() . '_' . $audioFile->getClientOriginalName());
            $audioFile->move(dirname($tempPath), basename($tempPath));
            
            \Log::info("Audio file saved to: $tempPath, Size: " . filesize($tempPath));
            
            // Kirim ke Python server - DIPERBAIKI
            $response = Http::timeout(120)->attach(
                'audio', 
                file_get_contents($tempPath),
                $audioFile->getClientOriginalName()
            )->post("{$this->pythonBaseUrl}/api/voice_chat", [
                'age_group' => $ageGroup,
                'user_id' => $userId
            ]);
            
            // Hapus file temp
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            if ($response->successful()) {
                // Get response headers
                $headers = $response->headers();
                $userText = $headers['X-STT-Text'][0] ?? 'Teks tidak terdeteksi';
                $aiResponse = $headers['X-AI-Response'][0] ?? 'Respons AI tidak tersedia';
                
                // Simpan ke history
                $this->saveToHistory(auth()->id(), [
                    'user_text' => $userText,
                    'ai_response' => $aiResponse,
                    'age_group' => $ageGroup,
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                // Return audio response
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache, no-store')
                    ->header('X-STT-Text', $userText)
                    ->header('X-AI-Response', $aiResponse)
                    ->header('X-Age-Group', $ageGroup)
                    ->header('Access-Control-Expose-Headers', 'X-STT-Text,X-AI-Response,X-Age-Group');
            }
            
            // Log error response
            \Log::error('Python server response failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            // Fallback: generate error message audio
            return $this->generateFallbackAudio("Maaf, server tidak merespons. Coba lagi nanti ya!");
            
        } catch (\Exception $e) {
            \Log::error('Voice processing error: ' . $e->getMessage());
            return $this->generateFallbackAudio("Maaf, terjadi kesalahan. Coba lagi ya!");
        }
    }
    
    /**
     * Text to Speech - DIPERBAIKI
     */
    public function textToSpeech(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string',
                'age_group' => 'nullable|string|in:3-5,5-7,7-9,9-12'
            ]);
            
            $text = $request->text;
            $ageGroup = $request->age_group ?? '5-7';
            
            // Kirim ke Python server
            $response = Http::post("{$this->pythonBaseUrl}/api/tts", [
                'text' => $text,
                'age_group' => $ageGroup
            ]);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache');
            }
            
            // Fallback menggunakan browser TTS
            return response()->json([
                'success' => false,
                'message' => 'Server tidak merespons, menggunakan browser TTS',
                'text' => $text,
                'age_group' => $ageGroup
            ]);
            
        } catch (\Exception $e) {
            \Log::error('TTS error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat suara: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Speech to Text - DIPERBAIKI
     */
    public function speechToText(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|file|mimes:wav,mp3,ogg,webm|max:5120'
            ]);
            
            $audioFile = $request->file('audio');
            
            // Kirim ke Python server
            $response = Http::attach(
                'audio', 
                file_get_contents($audioFile->path()), 
                $audioFile->getClientOriginalName()
            )->post("{$this->pythonBaseUrl}/api/stt");
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to convert speech',
                'status' => $response->status()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('STT error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengenali suara: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Text chat dengan AI - DIPERBAIKI
     */
    public function textChat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'age_group' => 'nullable|string|in:3-5,5-7,7-9,9-12',
                'user_id' => 'nullable|string'
            ]);
            
            $message = $request->message;
            $ageGroup = $request->age_group ?? '5-7';
            $userId = $request->user_id ?? 'user_' . auth()->id();
            
            // Kirim ke Python server
            $response = Http::post("{$this->pythonBaseUrl}/api/chat", [
                'message' => $message,
                'age_group' => $ageGroup,
                'user_id' => $userId
            ]);
            
            if ($response->successful()) {
                $headers = $response->headers();
                $aiResponse = $headers['X-AI-Response'][0] ?? $message;
                
                // Simpan ke history
                $this->saveToHistory(auth()->id(), [
                    'user_text' => $message,
                    'ai_response' => $aiResponse,
                    'age_group' => $ageGroup,
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache')
                    ->header('X-AI-Response', $aiResponse)
                    ->header('Access-Control-Expose-Headers', 'X-AI-Response');
            }
            
            // Fallback
            return $this->generateFallbackAudio("Halo! Saya Calista. Mari kita belajar bersama!");
            
        } catch (\Exception $e) {
            \Log::error('Text chat error: ' . $e->getMessage());
            return $this->generateFallbackAudio("Maaf, saya tidak bisa merespons sekarang.");
        }
    }
    
    /**
     * Check server health - DIPERBAIKI
     */
    public function checkServerHealth()
    {
        try {
            $response = Http::timeout(10)->get("{$this->pythonBaseUrl}/health");
            
            return response()->json([
                'server_url' => $this->pythonBaseUrl,
                'status' => $response->successful() ? 'online' : 'offline',
                'response' => $response->successful() ? $response->json() : null,
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'server_url' => $this->pythonBaseUrl,
                'status' => 'offline',
                'error' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }
    
    /**
     * Get conversation history
     */
    public function getHistory(Request $request)
    {
        try {
            $userId = auth()->id();
            $sessionId = $request->session_id ?? 'general';
            
            $history = Cache::get("voice_history_{$userId}_{$sessionId}", []);
            
            return response()->json([
                'success' => true,
                'history' => $history,
                'count' => count($history)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get history error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil riwayat'], 500);
        }
    }
    
    /**
     * Clear history
     */
    public function clearHistory(Request $request)
    {
        try {
            $userId = auth()->id();
            $sessionId = $request->session_id ?? 'general';
            
            Cache::forget("voice_history_{$userId}_{$sessionId}");
            
            return response()->json([
                'success' => true,
                'message' => 'Riwayat percakapan telah dihapus'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Clear history error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus riwayat'], 500);
        }
    }
    
    /**
     * Learning session dengan topik tertentu - DIPERBAIKI
     */
    public function learningSession(Request $request)
    {
        try {
            $request->validate([
                'topic' => 'required|string',
                'age_group' => 'nullable|string',
                'difficulty' => 'nullable|string'
            ]);
            
            $topic = $request->topic;
            $ageGroup = $request->age_group ?? '5-7';
            $difficulty = $request->difficulty ?? 'easy';
            
            // Generate greeting berdasarkan topik
            $greetingText = "Halo! Ayo kita belajar tentang {$topic} bersama Calista!";
            
            // Kirim ke Python server
            $response = Http::post("{$this->pythonBaseUrl}/api/tts", [
                'text' => $greetingText,
                'age_group' => $ageGroup
            ]);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache')
                    ->header('X-Topic', $topic)
                    ->header('X-Greeting', $greetingText);
            }
            
            return $this->generateFallbackAudio($greetingText);
            
        } catch (\Exception $e) {
            \Log::error('Learning session error: ' . $e->getMessage());
            return $this->generateFallbackAudio("Mari belajar bersama!");
        }
    }
    
    /**
     * Test audio generation - DIPERBAIKI
     */
    public function testAudio(Request $request)
    {
        try {
            $testText = $request->text ?? "Halo! Saya Calista, teman belajarmu yang ceria. Mari kita belajar bersama!";
            $ageGroup = $request->age_group ?? '5-7';
            
            // Kirim ke Python server
            $response = Http::post("{$this->pythonBaseUrl}/api/tts", [
                'text' => $testText,
                'age_group' => $ageGroup
            ]);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache')
                    ->header('X-Test-Text', $testText);
            }
            
            // Fallback menggunakan browser TTS
            return response()->json([
                'success' => false,
                'message' => 'Server tidak merespons, menggunakan browser TTS',
                'text' => $testText,
                'age_group' => $ageGroup
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Test audio error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal membuat audio test',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // ==================== HELPER METHODS ====================
    
    /**
     * Simpan ke history
     */
    private function saveToHistory($userId, $data)
    {
        $sessionId = session('voice_session_id', 'general');
        $cacheKey = "voice_history_{$userId}_{$sessionId}";
        
        $history = Cache::get($cacheKey, []);
        $history[] = $data;
        
        // Keep only last 20 messages
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }
        
        Cache::put($cacheKey, $history, now()->addDays(7));
    }
    
    /**
     * Generate fallback audio menggunakan Typecast atau TTS lokal
     */
    private function generateFallbackAudio($text)
    {
        // Coba gunakan Typecast TTS yang sudah ada di sistem
        try {
            $ttsController = new \App\Http\Controllers\TypecastController();
            $response = $ttsController->customTTS(new Request([
                'text' => $text,
                'character' => 'default'
            ]));
            
            if ($response instanceof \Illuminate\Http\Response) {
                return $response;
            }
        } catch (\Exception $e) {
            \Log::warning('Typecast fallback failed: ' . $e->getMessage());
        }
        
        // Jika semua gagal, return JSON dengan text untuk Web Speech API
        return response()->json([
            'success' => false,
            'message' => 'Menggunakan browser TTS',
            'text' => $text,
            'fallback' => true
        ]);
    }
    
    /**
     * Get active anak
     */
    private function getActiveAnak()
    {
        $user = auth()->user();
        return $user->anaks()->where('is_active', true)->first();
    }
}
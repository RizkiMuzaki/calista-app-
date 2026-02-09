<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Module;
use App\Models\Level;
use App\Models\WritingItem;
use App\Models\User;
use App\Models\Anak;
use Carbon\Carbon;

class MenulisAIController extends Controller
{
    private $pythonBaseUrl = 'http://localhost:5002';
    private $storyPythonUrl = 'http://localhost:5001';
    
    // Konfigurasi karakter dan suara
    private $characterConfig = [
        'default' => [
            'name' => 'Calista',
            'voice' => 'tc_641c10bfb62ae5eee6db3f9e', // child_female
            'greeting' => 'Halo! Aku Calista, teman menulismu!',
            'encouragement' => 'Wah, hebat sekali! Yuk lanjutkan menulisnya!'
        ],
        'teacher' => [
            'name' => 'Pak Guru',
            'voice' => 'tc_6426b6c0b62ae5eee6db3fa0', // child_male
            'greeting' => 'Selamat belajar menulis, nak!',
            'encouragement' => 'Bagus! Teruskan latihanmu!'
        ],
        'grandma' => [
            'name' => 'Nenek',
            'voice' => 'tc_6426b6c1b62ae5eee6db3fa2', // grandma
            'greeting' => 'Ayo nak, belajar menulis bersama nenek!',
            'encouragement' => 'Pintar sekali cucuku!'
        ]
    ];
    
    /**
     * Halaman utama menulis AI
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeAnak = $user->anaks()->where('is_active', true)->first();
        
        $modules = Module::where('type', 'writing')
            ->orWhere('slug', 'LIKE', 'menulis-%')
            ->orderBy('order_number')
            ->get();
        
        return view('pages.menulis-ai.index', compact('modules', 'activeAnak'));
    }
    
    /**
     * Halaman menulis interaktif dengan AI
     */
    public function showInteractiveWriting(Request $request, $slug)
    {
        $module = Module::where('slug', $slug)->firstOrFail();
        $user = auth()->user();
        $activeAnak = $user->anaks()->where('is_active', true)->first();
        
        // Generate session ID untuk menulis
        $sessionId = 'writing_' . $slug . '_' . $user->id . '_' . now()->timestamp;
        session(['writing_session_id' => $sessionId]);
        
        // Get atau create progress
        $progress = $this->getWritingProgress($user->id, $slug, $activeAnak);
        
        return view('pages.menulis-ai.interactive', compact('module', 'activeAnak', 'sessionId', 'progress'));
    }
    
    /**
     * Generate audio greeting untuk menulis
     */
    public function generateGreeting(Request $request)
    {
        try {
            $request->validate([
                'character' => 'nullable|string',
                'age_group' => 'nullable|string',
                'writing_topic' => 'nullable|string'
            ]);
            
            $character = $request->character ?? 'default';
            $ageGroup = $request->age_group ?? '5-7';
            $writingTopic = $request->writing_topic ?? 'menulis huruf';
            
            $characterConfig = $this->characterConfig[$character] ?? $this->characterConfig['default'];
            
            // Custom greeting berdasarkan topik
            $greetings = [
                'menulis huruf' => "Halo! Ayo kita belajar {$characterConfig['name']} bersama!",
                'menulis angka' => "Mari belajar angka dengan {$characterConfig['name']}!",
                'menulis kata' => "Siap-siap menulis kata dengan {$characterConfig['name']}!",
                'menulis kalimat' => "Yuk buat kalimat bersama {$characterConfig['name']}!",
            ];
            
            $greetingText = $greetings[$writingTopic] ?? $characterConfig['greeting'];
            
            // Tambahkan nama anak jika ada
            if ($activeAnak = $this->getActiveAnak()) {
                $greetingText = "Halo {$activeAnak->name}! " . $greetingText;
            }
            
            // Panggil Python API untuk generate audio
            $response = Http::post("{$this->pythonBaseUrl}/api/writing/greeting", [
                'text' => $greetingText,
                'character' => $character,
                'age_group' => $ageGroup,
                'voice_id' => $characterConfig['voice']
            ]);
            
            if ($response->successful()) {
                $audioContent = $response->body();
                
                // Simpan ke cache untuk sementara
                $cacheKey = 'writing_greeting_' . md5($greetingText . $character);
                Cache::put($cacheKey, base64_encode($audioContent), now()->addHours(1));
                
                return response($audioContent)
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('X-Greeting-Text', $greetingText)
                    ->header('X-Character', $characterConfig['name'])
                    ->header('X-Cache-Key', $cacheKey);
            }
            
            // Fallback ke TTS PHP jika Python down
            return $this->fallbackTTS($greetingText);
            
        } catch (\Exception $e) {
            \Log::error('Error generating greeting: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal membuat audio greeting'
            ], 500);
        }
    }
    
    /**
     * Generate instruksi menulis
     */
    public function generateInstruction(Request $request)
    {
        try {
            $request->validate([
                'writing_task' => 'required|string',
                'difficulty' => 'nullable|string|in:mudah,menengah,sulit',
                'character' => 'nullable|string'
            ]);
            
            $writingTask = $request->writing_task;
            $difficulty = $request->difficulty ?? 'mudah';
            $character = $request->character ?? 'default';
            
            $characterConfig = $this->characterConfig[$character] ?? $this->characterConfig['default'];
            
            // Format instruksi berdasarkan kesulitan
            $instructionTemplates = [
                'mudah' => "Ayo coba {$writingTask}. Ikuti garis putus-putus ini!",
                'menengah' => "Sekarang kita akan {$writingTask}. Perhatikan baik-baik ya!",
                'sulit' => "Mari {$writingTask}. Kamu pasti bisa!"
            ];
            
            $instructionText = $instructionTemplates[$difficulty] ?? "Ayo {$writingTask} bersama {$characterConfig['name']}!";
            
            // Panggil Python API
            $response = Http::post("{$this->pythonBaseUrl}/api/writing/instruction", [
                'text' => $instructionText,
                'character' => $character,
                'difficulty' => $difficulty,
                'voice_id' => $characterConfig['voice'],
                'emotion' => 'encouraging',
                'emotion_intensity' => 1.3
            ]);
            
            if ($response->successful()) {
                $audioContent = $response->body();
                
                return response($audioContent)
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('X-Instruction', $instructionText)
                    ->header('X-Difficulty', $difficulty);
            }
            
            return $this->fallbackTTS($instructionText);
            
        } catch (\Exception $e) {
            \Log::error('Error generating instruction: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat instruksi'], 500);
        }
    }
    
    /**
     * Generate pujian ketika berhasil menulis
     */
    public function generatePraise(Request $request)
    {
        try {
            $request->validate([
                'achievement' => 'required|string',
                'character' => 'nullable|string',
                'age_group' => 'nullable|string'
            ]);
            
            $achievement = $request->achievement; // 'huruf_a', 'angka_1', 'complete_word', dll
            $character = $request->character ?? 'default';
            $ageGroup = $request->age_group ?? '5-7';
            
            $characterConfig = $this->characterConfig[$character] ?? $this->characterConfig['default'];
            
            // Pujian berdasarkan pencapaian
            $praiseMessages = [
                'huruf_a' => ['Wah, huruf A-nya bagus sekali!', 'Hebat! Huruf A-nya sudah benar!', 'Sempurna! Kamu bisa menulis A!'],
                'huruf_b' => ['Bagus! Huruf B-nya sudah benar!', 'Luar biasa! B-nya sempurna!'],
                'angka_1' => ['Hore! Angka 1 sudah selesai!', 'Pintar! Angka 1-nya tepat!'],
                'complete_word' => ['Keren! Kata sudah lengkap!', 'Hebat! Kata sudah selesai ditulis!'],
                'complete_sentence' => ['Luar biasa! Kalimatnya sudah jadi!', 'Wah, kamu sudah bisa menulis kalimat!'],
                'perfect_stroke' => ['Goresannya sempurna!', 'Tekanan pensilnya pas sekali!'],
                'fast_completion' => ['Cepat sekali menyelesaikannya!', 'Kamu sangat rajin!']
            ];
            
            $achievementType = explode('_', $achievement)[0];
            $messages = $praiseMessages[$achievement] ?? 
                       $praiseMessages[$achievementType . '_default'] ?? 
                       ['Bagus sekali!', 'Kamu hebat!', 'Teruskan!'];
            
            $praiseText = $characterConfig['name'] . ' berkata: ' . $messages[array_rand($messages)];
            
            // Tambahkan nama anak jika ada
            if ($activeAnak = $this->getActiveAnak()) {
                $praiseText = str_replace('Kamu', $activeAnak->name, $praiseText);
            }
            
            // Panggil Python API dengan emotion yang lebih bersemangat
            $response = Http::post("{$this->pythonBaseUrl}/api/writing/praise", [
                'text' => $praiseText,
                'character' => $character,
                'age_group' => $ageGroup,
                'achievement' => $achievement,
                'voice_id' => $characterConfig['voice'],
                'emotion' => 'excited',
                'emotion_intensity' => 1.5,
                'pitch' => 7,
                'tempo' => 1.1
            ]);
            
            if ($response->successful()) {
                $audioContent = $response->body();
                
                // Simpan ke cache untuk replay
                $cacheKey = 'praise_' . $achievement . '_' . $character . '_' . md5($praiseText);
                Cache::put($cacheKey, base64_encode($audioContent), now()->addHours(2));
                
                return response($audioContent)
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('X-Praise-Text', $praiseText)
                    ->header('X-Achievement', $achievement)
                    ->header('X-Cache-Key', $cacheKey);
            }
            
            return $this->fallbackTTS($praiseText);
            
        } catch (\Exception $e) {
            \Log::error('Error generating praise: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat pujian'], 500);
        }
    }
    
    /**
     * Generate koreksi ketika salah menulis
     */
    public function generateCorrection(Request $request)
    {
        try {
            $request->validate([
                'mistake_type' => 'required|string',
                'correct_form' => 'required|string',
                'character' => 'nullable|string'
            ]);
            
            $mistakeType = $request->mistake_type; // 'stroke_order', 'letter_shape', 'spacing', 'direction'
            $correctForm = $request->correct_form;
            $character = $request->character ?? 'default';
            
            $characterConfig = $this->characterConfig[$character] ?? $this->characterConfig['default'];
            
            // Pesan koreksi yang friendly
            $correctionMessages = [
                'stroke_order' => ['Coba perhatikan urutan goresannya', 'Mulai dari atas dulu ya', 'Ikuti arah panahnya'],
                'letter_shape' => ['Bentuknya seperti ini', 'Perhatikan lengkungannya', 'Lihat contohnya'],
                'spacing' => ['Kasih jarak sedikit', 'Terlalu berdekatan', 'Renggangkan sedikit'],
                'direction' => ['Arahnya ke sini', 'Putar ke kanan', 'Ke bawah dulu'],
                'size' => ['Besar sedikit', 'Kecilkan dikit', 'Ukurannya sama ya'],
                'slant' => ['Miringnya ke sini', 'Tegakkan sedikit', 'Sudutnya seperti ini']
            ];
            
            $messages = $correctionMessages[$mistakeType] ?? ['Coba lagi ya', 'Perhatikan contohnya'];
            $correctionText = $messages[array_rand($messages)] . '. Seharusnya seperti ini: ' . $correctForm;
            
            // Format menjadi friendly
            $correctionText = $characterConfig['name'] . ' berkata: ' . $correctionText;
            
            // Panggil Python API dengan emotion supportive
            $response = Http::post("{$this->pythonBaseUrl}/api/writing/correction", [
                'text' => $correctionText,
                'character' => $character,
                'mistake_type' => $mistakeType,
                'voice_id' => $characterConfig['voice'],
                'emotion' => 'supportive',
                'emotion_intensity' => 1.2,
                'pitch' => 5,
                'tempo' => 0.9
            ]);
            
            if ($response->successful()) {
                $audioContent = $response->body();
                
                return response($audioContent)
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('X-Correction', $correctionText)
                    ->header('X-Mistake-Type', $mistakeType);
            }
            
            return $this->fallbackTTS($correctionText);
            
        } catch (\Exception $e) {
            \Log::error('Error generating correction: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat koreksi'], 500);
        }
    }
    
    /**
     * Generate cerita pendek untuk latihan menulis
     */
    public function generateStoryForWriting(Request $request)
    {
        try {
            $request->validate([
                'topic' => 'required|string',
                'age_group' => 'nullable|string',
                'length' => 'nullable|string|in:short,medium,long'
            ]);
            
            $topic = $request->topic;
            $ageGroup = $request->age_group ?? '5-7';
            $length = $request->length ?? 'short';
            
            // Panggil AI untuk generate cerita
            $response = Http::post("{$this->storyPythonUrl}/api/writing/story", [
                'topic' => $topic,
                'age_group' => $ageGroup,
                'length' => $length,
                'purpose' => 'writing_practice'
            ]);
            
            if ($response->successful()) {
                $storyData = $response->json();
                
                // Generate audio untuk cerita
                $audioResponse = Http::post("{$this->pythonBaseUrl}/api/writing/story-audio", [
                    'story_text' => $storyData['story'],
                    'age_group' => $ageGroup,
                    'character' => 'default'
                ]);
                
                if ($audioResponse->successful()) {
                    $audioContent = $audioResponse->body();
                    
                    // Simpan story dan audio ke cache
                    $cacheKey = 'writing_story_' . md5($topic . $ageGroup . $length);
                    Cache::put($cacheKey, [
                        'story' => $storyData['story'],
                        'words_to_practice' => $storyData['words_to_practice'] ?? [],
                        'audio' => base64_encode($audioContent),
                        'generated_at' => now()->toDateTimeString()
                    ], now()->addHours(3));
                    
                    return response($audioContent)
                        ->header('Content-Type', 'audio/mpeg')
                        ->header('X-Story-Topic', $topic)
                        ->header('X-Story-Length', strlen($storyData['story']))
                        ->header('X-Cache-Key', $cacheKey);
                }
            }
            
            // Fallback story
            $fallbackStory = "Hari ini kita akan belajar menulis tentang $topic. Ayo mulai!";
            return $this->fallbackTTS($fallbackStory);
            
        } catch (\Exception $e) {
            \Log::error('Error generating story: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat cerita'], 500);
        }
    }
    
    /**
     * Voice interaction untuk menulis (Speech-to-Text)
     */
    public function voiceInteraction(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|file|mimes:wav,mp3,ogg|max:5120',
                'context' => 'nullable|string',
                'writing_task' => 'nullable|string'
            ]);
            
            $audioFile = $request->file('audio');
            $context = $request->context ?? 'writing_practice';
            $writingTask = $request->writing_task ?? 'general';
            
            // Kirim audio ke Python untuk STT
            $response = Http::attach(
                'audio', 
                file_get_contents($audioFile->path()), 
                $audioFile->getClientOriginalName()
            )->post("{$this->pythonBaseUrl}/api/writing/stt", [
                'context' => $context,
                'writing_task' => $writingTask
            ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                // Process the text based on writing task
                $processedText = $this->processWritingCommand($result['text'], $writingTask);
                
                // Generate AI response
                $aiResponse = $this->generateAIResponseForWriting($processedText, $context);
                
                // Convert AI response to speech
                $ttsResponse = Http::post("{$this->pythonBaseUrl}/api/writing/tts", [
                    'text' => $aiResponse,
                    'character' => 'default',
                    'context' => $context
                ]);
                
                if ($ttsResponse->successful()) {
                    return response()->json([
                        'success' => true,
                        'user_text' => $result['text'],
                        'processed_text' => $processedText,
                        'ai_response' => $aiResponse,
                        'audio_content' => base64_encode($ttsResponse->body())
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Gagal memproses suara'
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('Voice interaction error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses interaksi suara'], 500);
        }
    }
    
    /**
     * Simpan progress menulis
     */
    public function saveProgress(Request $request)
    {
        try {
            $request->validate([
                'module_slug' => 'required|string',
                'level_id' => 'nullable|integer',
                'writing_item_id' => 'nullable|integer',
                'progress_data' => 'required|array',
                'score' => 'nullable|numeric',
                'time_spent' => 'nullable|integer'
            ]);
            
            $user = auth()->user();
            $activeAnak = $this->getActiveAnak();
            
            if (!$activeAnak) {
                return response()->json(['error' => 'Tidak ada anak aktif'], 400);
            }
            
            $progressData = [
                'user_id' => $user->id,
                'anak_id' => $activeAnak->id,
                'module_slug' => $request->module_slug,
                'level_id' => $request->level_id,
                'writing_item_id' => $request->writing_item_id,
                'progress_data' => json_encode($request->progress_data),
                'score' => $request->score ?? 0,
                'time_spent' => $request->time_spent ?? 0,
                'completed_at' => now()
            ];
            
            // Simpan ke database (atau cache jika belum ada tabel)
            $cacheKey = 'writing_progress_' . $user->id . '_' . $activeAnak->id . '_' . $request->module_slug;
            Cache::put($cacheKey, $progressData, now()->addDays(30));
            
            // Update XP anak
            $xpEarned = $this->calculateXPEarned($request->score ?? 0, $request->time_spent ?? 0);
            $activeAnak->increment('xp', $xpEarned);
            
            // Generate audio feedback berdasarkan score
            $feedbackAudio = $this->generateProgressFeedback($request->score ?? 0, $xpEarned);
            
            return response()->json([
                'success' => true,
                'message' => 'Progress disimpan',
                'xp_earned' => $xpEarned,
                'total_xp' => $activeAnak->xp,
                'next_level_xp' => $this->getNextLevelXP($activeAnak->xp),
                'feedback_audio' => $feedbackAudio ? base64_encode($feedbackAudio) : null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error saving progress: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan progress'], 500);
        }
    }
    
    /**
     * Dapatkan progress menulis
     */
    public function getProgress(Request $request)
    {
        try {
            $request->validate([
                'module_slug' => 'nullable|string'
            ]);
            
            $user = auth()->user();
            $activeAnak = $this->getActiveAnak();
            
            if (!$activeAnak) {
                return response()->json(['error' => 'Tidak ada anak aktif'], 400);
            }
            
            $moduleSlug = $request->module_slug;
            $cacheKey = 'writing_progress_' . $user->id . '_' . $activeAnak->id;
            
            if ($moduleSlug) {
                $cacheKey .= '_' . $moduleSlug;
                $progress = Cache::get($cacheKey);
                
                return response()->json([
                    'success' => true,
                    'progress' => $progress,
                    'module_slug' => $moduleSlug
                ]);
            }
            
            // Get all progress for this user and anak
            $allProgress = [];
            $cacheKeys = Cache::get('writing_progress_keys_' . $user->id . '_' . $activeAnak->id, []);
            
            foreach ($cacheKeys as $key) {
                if ($progress = Cache::get($key)) {
                    $allProgress[] = $progress;
                }
            }
            
            return response()->json([
                'success' => true,
                'total_progress' => count($allProgress),
                'progress_list' => $allProgress,
                'total_xp' => $activeAnak->xp,
                'level' => $this->calculateLevel($activeAnak->xp)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting progress: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil progress'], 500);
        }
    }
    
    /**
     * Generate exercise menulis berdasarkan level
     */
    public function generateExercise(Request $request)
    {
        try {
            $request->validate([
                'level' => 'required|string|in:beginner,intermediate,advanced',
                'type' => 'nullable|string|in:letters,numbers,words,sentences',
                'topic' => 'nullable|string'
            ]);
            
            $level = $request->level;
            $type = $request->type ?? 'letters';
            $topic = $request->topic;
            
            // Panggil AI untuk generate exercise
            $response = Http::post("{$this->pythonBaseUrl}/api/writing/exercise", [
                'level' => $level,
                'type' => $type,
                'topic' => $topic,
                'language' => 'id'
            ]);
            
            if ($response->successful()) {
                $exerciseData = $response->json();
                
                // Generate audio instruksi
                $instructionText = "Ayo kita latihan menulis! " . ($exerciseData['instruction'] ?? 'Ikuti contoh di bawah ini.');
                
                $audioResponse = Http::post("{$this->pythonBaseUrl}/api/writing/instruction", [
                    'text' => $instructionText,
                    'character' => 'default',
                    'difficulty' => $level
                ]);
                
                $exerciseData['audio_instruction'] = $audioResponse->successful() ? 
                    base64_encode($audioResponse->body()) : null;
                
                return response()->json([
                    'success' => true,
                    'exercise' => $exerciseData,
                    'generated_at' => now()->toDateTimeString()
                ]);
            }
            
            // Fallback exercise
            $fallbackExercise = [
                'content' => $type === 'letters' ? 'A B C' : ($type === 'numbers' ? '1 2 3' : 'ibu ayah'),
                'instruction' => 'Tulislah dengan rapi',
                'hints' => ['Mulai dari kiri', 'Ikuti garis'],
                'expected_result' => $type === 'letters' ? 'A' : ($type === 'numbers' ? '1' : 'ibu')
            ];
            
            return response()->json([
                'success' => true,
                'exercise' => $fallbackExercise,
                'is_fallback' => true
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error generating exercise: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat latihan'], 500);
        }
    }
    
    /**
     * Test koneksi ke Python server
     */
    public function testConnection()
    {
        try {
            // Test writing server
            $writingResponse = Http::get("{$this->pythonBaseUrl}/api/health");
            $writingStatus = $writingResponse->successful() ? 'connected' : 'disconnected';
            
            // Test story server
            $storyResponse = Http::get("{$this->storyPythonUrl}/");
            $storyStatus = $storyResponse->successful() ? 'connected' : 'disconnected';
            
            return response()->json([
                'writing_server' => [
                    'url' => $this->pythonBaseUrl,
                    'status' => $writingStatus,
                    'response_time' => $writingResponse->successful() ? $writingResponse->handlerStats()['total_time'] ?? 'N/A' : 'N/A'
                ],
                'story_server' => [
                    'url' => $this->storyPythonUrl,
                    'status' => $storyStatus,
                    'response_time' => $storyResponse->successful() ? $storyResponse->handlerStats()['total_time'] ?? 'N/A' : 'N/A'
                ],
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'writing_server' => ['url' => $this->pythonBaseUrl, 'status' => 'error'],
                'story_server' => ['url' => $this->storyPythonUrl, 'status' => 'error']
            ], 500);
        }
    }
    
    /**
     * Clear cache audio
     */
    public function clearAudioCache(Request $request)
    {
        try {
            $cacheKey = $request->cache_key;
            
            if ($cacheKey) {
                Cache::forget($cacheKey);
                $message = "Cache dengan key {$cacheKey} dihapus";
            } else {
                // Clear all writing audio cache
                $prefix = 'writing_';
                $cleared = 0;
                
                // This is simplified - in production use Redis scan or similar
                for ($i = 0; $i < 100; $i++) {
                    $testKey = $prefix . $i;
                    if (Cache::has($testKey)) {
                        Cache::forget($testKey);
                        $cleared++;
                    }
                }
                
                $message = "{$cleared} cache items cleared";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error clearing cache: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus cache'], 500);
        }
    }
    
    // ==================== HELPER METHODS ====================
    
    private function getActiveAnak()
    {
        $user = auth()->user();
        return $user->anaks()->where('is_active', true)->first();
    }
    
    private function getWritingProgress($userId, $moduleSlug, $anak = null)
    {
        $cacheKey = 'writing_progress_' . $userId . '_' . ($anak ? $anak->id : '0') . '_' . $moduleSlug;
        return Cache::get($cacheKey, [
            'current_page' => 1,
            'completed_pages' => [],
            'scores' => [],
            'total_score' => 0,
            'started_at' => now()->toDateTimeString()
        ]);
    }
    
    private function processWritingCommand($text, $writingTask)
    {
        $text = strtolower(trim($text));
        
        // Command mapping untuk menulis
        $commands = [
            'mulai' => 'start_writing',
            'selesai' => 'finish_writing',
            'ulangi' => 'repeat_exercise',
            'contoh' => 'show_example',
            'bantuan' => 'need_help',
            'lanjut' => 'continue_next',
            'koreksi' => 'request_correction',
            'nilai' => 'request_score'
        ];
        
        foreach ($commands as $idCommand => $action) {
            if (strpos($text, $idCommand) !== false) {
                return $action;
            }
        }
        
        // Check for specific writing content
        if (preg_match('/[a-z]/', $text) || preg_match('/[0-9]/', $text)) {
            return 'writing_content:' . $text;
        }
        
        return 'unknown:' . $text;
    }
    
    private function generateAIResponseForWriting($processedText, $context)
    {
        $responses = [
            'start_writing' => ['Ayo mulai menulis!', 'Siap-siap menulis ya!', 'Mari kita mulai!'],
            'finish_writing' => ['Sudah selesai? Hebat!', 'Selesai menulis, bagus!', 'Wah, sudah jadi!'],
            'repeat_exercise' => ['Oke, kita ulangi lagi', 'Mari kita coba sekali lagi', 'Ulangi ya, pasti bisa!'],
            'show_example' => ['Ini contohnya', 'Lihat baik-baik contohnya', 'Perhatikan contoh ini'],
            'need_help' => ['Aku bantu ya', 'Mau bantuan apa?', 'Di mana kesulitannya?'],
            'continue_next' => ['Lanjut ke halaman berikutnya', 'Mari lanjutkan', 'Sekarang yang berikutnya'],
            'request_correction' => ['Aku periksa dulu ya', 'Coba lihat di sini', 'Mari kita perbaiki bersama'],
            'request_score' => ['Kamu dapat nilai bagus!', 'Hasilnya sangat baik!', 'Pekerjaan yang rapi!']
        ];
        
        $defaultResponses = ['Bagus!', 'Lanjutkan!', 'Hebat!'];
        
        if (strpos($processedText, 'writing_content:') === 0) {
            $content = substr($processedText, 15);
            return "Kamu menulis: " . strtoupper($content) . ". Bagus!";
        }
        
        $responseList = $responses[$processedText] ?? $defaultResponses;
        return $responseList[array_rand($responseList)];
    }
    
    private function calculateXPEarned($score, $timeSpent)
    {
        // Base XP dari score (0-100 -> 0-50 XP)
        $scoreXP = floor($score / 2);
        
        // Bonus XP untuk waktu cepat (kurang dari 5 menit)
        $timeBonus = $timeSpent > 0 && $timeSpent < 300 ? 10 : 0;
        
        // Bonus XP untuk perfect score
        $perfectBonus = $score >= 95 ? 15 : 0;
        
        return $scoreXP + $timeBonus + $perfectBonus;
    }
    
    private function calculateLevel($xp)
    {
        $levels = [
            0 => 'Pemula',
            100 => 'Pandai',
            300 => 'Ahli',
            600 => 'Master',
            1000 => 'Legenda'
        ];
        
        $currentLevel = 'Pemula';
        foreach ($levels as $requiredXP => $levelName) {
            if ($xp >= $requiredXP) {
                $currentLevel = $levelName;
            }
        }
        
        return $currentLevel;
    }
    
    private function getNextLevelXP($currentXP)
    {
        $thresholds = [100, 300, 600, 1000];
        
        foreach ($thresholds as $threshold) {
            if ($currentXP < $threshold) {
                return $threshold;
            }
        }
        
        return null; // Sudah level maksimal
    }
    
    private function generateProgressFeedback($score, $xpEarned)
    {
        if ($score >= 90) {
            $feedback = "Luar biasa! Nilai {$score}! Kamu dapat {$xpEarned} XP!";
        } elseif ($score >= 70) {
            $feedback = "Bagus! Nilai {$score}. Kamu dapat {$xpEarned} XP.";
        } else {
            $feedback = "Coba lagi ya. Nilai {$score}. Tetap dapat {$xpEarned} XP untuk usaha.";
        }
        
        try {
            $response = Http::post("{$this->pythonBaseUrl}/api/writing/tts", [
                'text' => $feedback,
                'character' => 'default',
                'emotion' => $score >= 70 ? 'excited' : 'encouraging'
            ]);
            
            return $response->successful() ? $response->body() : null;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function fallbackTTS($text)
    {
        // Simple fallback menggunakan Google TTS API (gratis)
        try {
            $encodedText = urlencode($text);
            $url = "https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&tl=id&q={$encodedText}";
            
            $response = Http::get($url);
            
            if ($response->successful()) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('X-Fallback-TTS', 'true');
            }
        } catch (\Exception $e) {
            // Ignore fallback error
        }
        
        // Return silent audio sebagai last resort
        return $this->generateSilentAudio();
    }
    
    private function generateSilentAudio()
    {
        // Generate 0.5 detik audio silence
        $silence = str_repeat("\x00", 8000); // 8000 bytes untuk ~0.5 detik
        
        return response($silence)
            ->header('Content-Type', 'audio/mpeg')
            ->header('X-Silent-Audio', 'true');
    }
}
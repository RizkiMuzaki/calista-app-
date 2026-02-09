<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\StoryPage;
use App\Models\StoryChoice;
use App\Models\StoryImage;
use App\Models\UserStoryProgress;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CeritaRakyatAIController extends Controller
{
    private $pythonApiUrl = 'https://grand-discrete-marten.ngrok-free.app'; // Python Flask API (Cerita Rakyat)
    
    /**
     * Display interactive story with AI features
     */
    public function showInteractiveStory($slug)
    {
        try {
            // Get the book/story
            $book = Book::where('slug', $slug)
                        ->where('type', 'cerita')
                        ->firstOrFail();
            
            // Get all story pages with relationships
            $storyPages = StoryPage::where('book_id', $book->id)
                ->orderBy('page_number', 'asc')
                ->with(['choices', 'storyImages'])
                ->get();
            
            // Pre-generate audio for pages that don't have it
            foreach ($storyPages as $page) {
                $this->ensurePageAudioExists($book, $page);
            }
            
            return view('pages.detailceritarakyat', [
                'book' => $book,
                'storyPages' => $storyPages
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing interactive story: ' . $e->getMessage());
            return redirect()->route('buku-membaca.index')
                ->with('error', 'Cerita tidak ditemukan: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate audio for story page if not exists
     */
    private function ensurePageAudioExists($book, $page)
    {
        try {
            // Check if audio already exists
            if (!empty($page->audio_path) && Storage::disk('public')->exists($page->audio_path)) {
                return true;
            }
            
            // Generate audio using Python API
            $response = Http::timeout(120)->post($this->pythonApiUrl . '/api/story/audio', [
                'story_id' => $book->slug,
                'page_number' => $page->page_number,
                'story_text' => $page->story_text,
                'age_group' => '5-7',
                'save_to_cache' => true
            ]);
            
            if ($response->successful() && strpos($response->header('Content-Type'), 'audio/mpeg') !== false) {
                // Save audio to storage
                $audioContent = $response->body();
                $fileName = 'story_audio/' . $book->slug . '/' . $page->page_number . '_' . time() . '.mp3';
                
                // Ensure directory exists
                Storage::disk('public')->makeDirectory('story_audio/' . $book->slug);
                
                // Save file
                Storage::disk('public')->put($fileName, $audioContent);
                
                // Update page with audio path
                $page->audio_path = $fileName;
                $page->save();
                
                Log::info("Generated TTS audio for {$book->slug} page {$page->page_number}");
                
                return true;
            } else {
                Log::error("Failed to generate TTS for {$book->slug} page {$page->page_number}: " . $response->status());
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error("Error generating audio for {$book->slug} page {$page->page_number}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate real-time TTS for question (not saved)
     */
    public function generateQuestionTTS(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'age_group' => 'string|in:3-5,5-7,7-9,9-12'
        ]);
        
        try {
            $question = $request->question;
            $ageGroup = $request->age_group ?? '5-7';
            
            // Generate TTS using Python API
            $response = Http::timeout(60)->post($this->pythonApiUrl . '/api/story/question', [
                'question' => $question,
                'age_group' => $ageGroup
            ]);
            
            if ($response->successful() && strpos($response->header('Content-Type'), 'audio/mpeg') !== false) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache, no-store')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0')
                    ->header('X-Audio-Type', 'question-tts')
                    ->header('X-Question-Text', $question);
            } else {
                throw new \Exception('Python API returned error: ' . $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('Error generating question TTS: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghasilkan audio pertanyaan'], 500);
        }
    }
    
    /**
     * Generate real-time TTS for choice (not saved)
     */
    public function generateChoiceTTS(Request $request)
    {
        $request->validate([
            'choice_text' => 'required|string',
            'age_group' => 'string|in:3-5,5-7,7-9,9-12'
        ]);
        
        try {
            $choiceText = $request->choice_text;
            $ageGroup = $request->age_group ?? '5-7';
            
            // Generate TTS using Python API
            $response = Http::timeout(60)->post($this->pythonApiUrl . '/api/story/choice', [
                'choice_text' => $choiceText,
                'age_group' => $ageGroup
            ]);
            
            if ($response->successful() && strpos($response->header('Content-Type'), 'audio/mpeg') !== false) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache, no-store')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0')
                    ->header('X-Audio-Type', 'choice-tts')
                    ->header('X-Choice-Text', $choiceText);
            } else {
                throw new \Exception('Python API returned error: ' . $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('Error generating choice TTS: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghasilkan audio pilihan'], 500);
        }
    }
    
    /**
     * Process voice interaction with story character
     */
    public function processVoiceInteraction(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,m4a|max:10240',
            'story_context' => 'required|string',
            'current_page' => 'required|integer',
            'story_id' => 'required|string',
            'age_group' => 'string|in:3-5,5-7,7-9,9-12',
            'character_name' => 'string'
        ]);
        
        try {
            $audioFile = $request->file('audio');
            $storyContext = $request->story_context;
            $currentPage = $request->current_page;
            $storyId = $request->story_id;
            $ageGroup = $request->age_group ?? '5-7';
            $characterName = $request->character_name ?? 'Calista';
            $userId = 'user_' . auth()->id();
            
            // Send to Python Voice Agent API with proper multipart form data
            $response = Http::timeout(180)->multipart([
                [
                    'name' => 'audio',
                    'contents' => file_get_contents($audioFile->getRealPath()),
                    'filename' => $audioFile->getClientOriginalName()
                ],
                [
                    'name' => 'story_context',
                    'contents' => $storyContext
                ],
                [
                    'name' => 'current_page',
                    'contents' => (string)$currentPage
                ],
                [
                    'name' => 'age_group',
                    'contents' => $ageGroup
                ],
                [
                    'name' => 'user_id',
                    'contents' => $userId
                ],
                [
                    'name' => 'character_name',
                    'contents' => $characterName
                ]
            ])->post($this->pythonApiUrl . '/api/story/voice');
            
            if ($response->successful() && strpos($response->header('Content-Type'), 'audio/mpeg') !== false) {
                // Get metadata from headers
                $sttText = $response->header('X-STT-Text', '');
                $aiResponse = $response->header('X-AI-Response', '');
                $characterName = $response->header('X-Character-Name', 'Calista');
                
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache, no-store')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0')
                    ->header('X-STT-Text', $sttText)
                    ->header('X-AI-Response', $aiResponse)
                    ->header('X-Character-Name', $characterName)
                    ->header('X-Story-ID', $storyId)
                    ->header('X-Current-Page', $currentPage);
            } else {
                throw new \Exception('Voice Agent API returned error: ' . $response->status());
            }
            
        } catch (\Exception $e) {
            Log::error('Error processing voice interaction: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses interaksi suara: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get next page based on current state
     */
    public function getNextPage(Request $request, $slug)
    {
        $request->validate([
            'current_page' => 'required|integer',
            'choice_id' => 'nullable|integer',
            'skip_choices' => 'boolean',
            'has_question' => 'boolean'
        ]);
        
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            $currentPage = $request->current_page;
            $choiceId = $request->choice_id;
            $skipChoices = $request->skip_choices ?? false;
            $hasQuestion = $request->has_question ?? false;
            
            // Get current page
            $page = StoryPage::where('book_id', $book->id)
                            ->where('page_number', $currentPage)
                            ->with('choices')
                            ->first();
            
            if (!$page) {
                return response()->json(['error' => 'Halaman tidak ditemukan'], 404);
            }
            
            // If page has a question and we should skip choices, use next_page_number
            if ($hasQuestion && $skipChoices && !empty($page->next_page_number)) {
                $nextPage = StoryPage::where('book_id', $book->id)
                                    ->where('page_number', $page->next_page_number)
                                    ->first();
                
                if ($nextPage) {
                    return response()->json([
                        'success' => true,
                        'next_page' => $nextPage->page_number,
                        'has_question' => !empty($nextPage->question),
                        'question' => $nextPage->question,
                        'auto_advance' => true,
                        'message' => 'Melanjutkan ke halaman berikutnya'
                    ]);
                }
            }
            
            // If a choice was made
            if ($choiceId) {
                $choice = StoryChoice::find($choiceId);
                
                if ($choice && $choice->story_page_id == $page->id) {
                    $nextPage = StoryPage::where('book_id', $book->id)
                                        ->where('page_number', $choice->next_page_number)
                                        ->first();
                    
                    if ($nextPage) {
                        return response()->json([
                            'success' => true,
                            'next_page' => $nextPage->page_number,
                            'has_question' => !empty($nextPage->question),
                            'choice_made' => true,
                            'choice_text' => $choice->choice_text,
                            'message' => 'Pilihan dipilih'
                        ]);
                    }
                }
            }
            
            // Default: go to next sequential page
            $nextPage = StoryPage::where('book_id', $book->id)
                                ->where('page_number', '>', $currentPage)
                                ->orderBy('page_number', 'asc')
                                ->first();
            
            if ($nextPage) {
                return response()->json([
                    'success' => true,
                    'next_page' => $nextPage->page_number,
                    'has_question' => !empty($nextPage->question),
                    'auto_advance' => true,
                    'message' => 'Halaman berikutnya'
                ]);
            }
            
            // Story completed
            return response()->json([
                'success' => true,
                'next_page' => null,
                'story_completed' => true,
                'message' => '🎉 Cerita selesai! Kamu hebat!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting next page: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mendapatkan halaman berikutnya: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get story images for a page
     */
    public function getStoryImages($slug, $pageNumber)
    {
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            
            $page = StoryPage::where('book_id', $book->id)
                            ->where('page_number', $pageNumber)
                            ->with('storyImages')
                            ->first();
            
            if (!$page) {
                return response()->json(['error' => 'Halaman tidak ditemukan'], 404);
            }
            
            $images = [];
            foreach ($page->storyImages as $image) {
                $images[] = [
                    'id' => $image->id,
                    'image_path' => Storage::url($image->image_path),
                    'type' => $image->type,
                    'position_x' => $image->position_x,
                    'position_y' => $image->position_y,
                    'order' => $image->order,
                    'animation' => $image->animation
                ];
            }
            
            return response()->json([
                'success' => true,
                'page_number' => $page->page_number,
                'images' => $images,
                'has_question' => !empty($page->question),
                'question' => $page->question,
                'next_page_number' => $page->next_page_number
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting story images: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mendapatkan gambar: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get page details with audio information
     */
    public function getPageDetails($slug, $pageNumber)
    {
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            
            $page = StoryPage::where('book_id', $book->id)
                            ->where('page_number', $pageNumber)
                            ->with('choices')
                            ->firstOrFail();
            
            // Ensure audio exists
            $hasAudio = $this->ensurePageAudioExists($book, $page);
            $page->refresh();
            
            return response()->json([
                'success' => true,
                'page' => [
                    'page_number' => $page->page_number,
                    'story_text' => $page->story_text,
                    'question' => $page->question,
                    'audio_path' => $page->audio_path ? Storage::url($page->audio_path) : null,
                    'audio_exists' => $hasAudio,
                    'choices' => $page->choices->map(function($choice) {
                        return [
                            'id' => $choice->id,
                            'choice_text' => $choice->choice_text,
                            'next_page_number' => $choice->next_page_number,
                            'audio_path' => $choice->audio_path ? Storage::url($choice->audio_path) : null
                        ];
                    })
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting page details: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mendapatkan detail halaman'], 500);
        }
    }
    
    /**
     * Play specific page audio
     */
    public function playPageAudio($slug, $pageNumber)
    {
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            
            $page = StoryPage::where('book_id', $book->id)
                            ->where('page_number', $pageNumber)
                            ->firstOrFail();
            
            // Ensure audio exists
            $this->ensurePageAudioExists($book, $page);
            $page->refresh();
            
            if (!$page->audio_path || !Storage::disk('public')->exists($page->audio_path)) {
                return response()->json([
                    'error' => 'Audio tidak ditemukan'
                ], 404);
            }
            
            $path = Storage::disk('public')->path($page->audio_path);
            
            return response()->file($path, [
                'Content-Type' => 'audio/mpeg',
                'Cache-Control' => 'no-cache, no-store',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Audio-Type' => 'story-page',
                'X-Page-Number' => $pageNumber,
                'X-Story-Slug' => $slug
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error playing page audio: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memutar audio halaman'], 500);
        }
    }
    
    /**
     * Save page audio from Python API
     */
    public function savePageAudio(Request $request, $slug)
    {
        $request->validate([
            'page_number' => 'required|integer',
            'audio' => 'required|file|mimes:mp3,wav'
        ]);
        
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            $pageNumber = $request->page_number;
            
            $page = StoryPage::where('book_id', $book->id)
                            ->where('page_number', $pageNumber)
                            ->firstOrFail();
            
            $audioFile = $request->file('audio');
            $fileName = 'story_audio/' . $book->slug . '/' . $pageNumber . '_' . time() . '.mp3';
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('story_audio/' . $book->slug);
            
            // Save file
            Storage::disk('public')->put($fileName, file_get_contents($audioFile->getRealPath()));
            
            // Update page with audio path
            $page->audio_path = $fileName;
            $page->save();
            
            Log::info("Saved audio for {$slug} page {$pageNumber}");
            
            return response()->json([
                'success' => true,
                'message' => 'Audio berhasil disimpan',
                'audio_url' => Storage::url($fileName),
                'audio_path' => $fileName
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saving page audio: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan audio halaman'], 500);
        }
    }
    
    /**
     * Play audio sequence (story audio then question audio)
     */
    public function playAudioSequence(Request $request)
    {
        $request->validate([
            'story_id' => 'required|string',
            'page_number' => 'required|integer',
            'age_group' => 'string|in:3-5,5-7,7-9,9-12'
        ]);
        
        try {
            $storyId = $request->story_id;
            $pageNumber = $request->page_number;
            $ageGroup = $request->age_group ?? '5-7';
            
            $book = Book::where('slug', $storyId)->firstOrFail();
            $page = StoryPage::where('book_id', $book->id)
                            ->where('page_number', $pageNumber)
                            ->firstOrFail();
            
            $responseData = [
                'success' => true,
                'page_number' => $pageNumber,
                'story_text' => $page->story_text,
                'question' => $page->question,
                'has_question' => !empty($page->question),
                'audio_sequence' => []
            ];
            
            // 1. Add story audio first
            if ($page->audio_path && Storage::disk('public')->exists($page->audio_path)) {
                $responseData['audio_sequence'][] = [
                    'type' => 'story',
                    'url' => Storage::url($page->audio_path),
                    'text' => $page->story_text
                ];
            }
            
            
            // 2. Add question audio after story (if exists)
            if (!empty($page->question)) {
                // Check if we have question audio saved
                $questionAudioPath = null;
                
                // Try to get from cache or generate
                $questionAudioUrl = url('/cerita-ai/question-tts');
                $responseData['audio_sequence'][] = [
                    'type' => 'question',
                    'url' => $questionAudioUrl,
                    'text' => $page->question,
                    'requires_generation' => true
                ];
            }
            
            return response()->json($responseData);
            
        } catch (\Exception $e) {
            Log::error('Error in playAudioSequence: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses urutan audio'], 500);
        }
    }
    
    /**
     * Complete story and save progress
     */
    public function completeStory(Request $request, $slug)
    {
        $request->validate([
            'current_page' => 'required|integer',
            'score' => 'integer|min:0|max:100'
        ]);
        
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            $user = auth()->user();
            $currentPage = $request->current_page;
            $score = $request->score ?? 100;
            
            // Save user progress
            $progress = UserStoryProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'book_id' => $book->id
                ],
                [
                    'current_page' => $currentPage,
                    'completed' => true,
                    'score' => $score,
                    'completed_at' => now()
                ]
            );
            
            // Generate congratulatory message
            $congratsText = "Hore! Kamu telah menyelesaikan cerita {$book->title}. Skor kamu: {$score}. Hebat sekali!";
            
            // Get congratulatory audio from Python API
            $response = Http::timeout(60)->post($this->pythonApiUrl . '/api/story/audio', [
                'story_id' => 'congratulations',
                'page_number' => 1,
                'story_text' => $congratsText,
                'age_group' => '5-7',
                'save_to_cache' => false
            ]);
            
            if ($response->successful() && strpos($response->header('Content-Type'), 'audio/mpeg') !== false) {
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Cache-Control', 'no-cache, no-store');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Cerita selesai!',
                'progress' => $progress,
                'score' => $score
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error completing story: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyelesaikan cerita: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get user's story progress
     */
    public function getProgress(Request $request, $slug)
    {
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            $user = auth()->user();
            
            $progress = UserStoryProgress::where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->first();
            
            if ($progress) {
                return response()->json([
                    'success' => true,
                    'progress' => [
                        'current_page' => $progress->current_page,
                        'completed' => $progress->completed,
                        'score' => $progress->score,
                        'last_updated' => $progress->updated_at->format('Y-m-d H:i:s')
                    ]
                ]);
            }
            
            return response()->json([
                'success' => true,
                'progress' => null,
                'message' => 'Belum ada progress'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting progress: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mendapatkan progress: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Test connection to Python API
     */
    public function testPythonConnection()
    {
        try {
            $response = Http::timeout(10)->get($this->pythonApiUrl);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Python API connected successfully',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Python API returned error',
                    'status' => $response->status()
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot connect to Python API',
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Clear audio cache for a story
     */
    public function clearAudioCache($slug)
    {
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            
            // Clear via Python API
            $response = Http::post($this->pythonApiUrl . '/api/story/cache/clear', [
                'story_id' => $book->slug
            ]);
            
            // Also clear local audio files
            $pages = StoryPage::where('book_id', $book->id)->get();
            foreach ($pages as $page) {
                if (!empty($page->audio_path)) {
                    Storage::disk('public')->delete($page->audio_path);
                    $page->audio_path = null;
                    $page->save();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Audio cache cleared',
                'python_response' => $response->json()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error clearing audio cache: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus cache audio'], 500);
        }
    }
    
    /**
     * Generate audio for specific page
     */
    public function generatePageAudio(Request $request, $slug)
    {
        $request->validate([
            'page_number' => 'required|integer',
            'force_regenerate' => 'boolean'
        ]);
        
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            $pageNumber = $request->page_number;
            $forceRegenerate = $request->force_regenerate ?? false;
            
            $page = StoryPage::where('book_id', $book->id)
                            ->where('page_number', $pageNumber)
                            ->firstOrFail();
            
            // Check if audio already exists and we shouldn't regenerate
            if (!$forceRegenerate && !empty($page->audio_path) && Storage::disk('public')->exists($page->audio_path)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Audio sudah ada',
                    'audio_url' => Storage::url($page->audio_path)
                ]);
            }
            
            // Generate new audio
            $this->ensurePageAudioExists($book, $page);
            
            // Refresh page data
            $page->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Audio berhasil di-generate',
                'audio_url' => $page->audio_path ? Storage::url($page->audio_path) : null,
                'page' => $page
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating page audio: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghasilkan audio halaman'], 500);
        }
    }
}
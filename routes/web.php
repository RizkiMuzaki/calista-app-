<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnakController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\NusaController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PageBookController;
use App\Http\Controllers\TypecastController;
use App\Http\Controllers\VoiceAgentController;
use App\Http\Controllers\PaymentPlanController;
use App\Http\Controllers\ProgresAnakController;
use App\Http\Controllers\MenghitungAIController;
use App\Http\Controllers\CeritaRakyatAIController;
use App\Http\Controllers\ArtikelController; // Tambah ini
use App\Http\Controllers\PremiumController; // Tambah ini
use App\Http\Controllers\MenulisAIController; // Tambah ini
use App\Http\Controllers\GameController; // Tambah ini

Route::get('/animated-character', function () {
    return view('animated-character');
});

Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])
    ->name('payment.callback')
    ->withoutMiddleware(['web', 'auth', 'verifycsrf']); // Nonaktifkan semua middleware


Route::get('/', function () {
    return view('welcome');
});

// ==================== ARTIKEL ROUTES (PUBLIC - NO LOGIN REQUIRED) ====================
Route::prefix('artikel')->group(function () {
    Route::get('/', [ArtikelController::class, 'index'])->name('artikel.index');
    Route::get('/{slug}', [ArtikelController::class, 'show'])->name('artikel.show');
});

  Route::prefix('plan')->group(function () {
        Route::get('/payment/page', [PaymentPlanController::class, 'showPaymentPage'])->name('plan.payment.page');
        Route::post('/payment/create', [PaymentPlanController::class, 'createPlanPayment'])->name('plan.payment.create');
        Route::get('/payment/status/{reference?}', [PaymentController::class, 'paymentReturn'])->name('plan.payment.status');
    });
   // Payment Routes
    Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/status', [PaymentController::class, 'paymentReturn'])->name('payment.return');
    Route::post('/payment/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
    Route::get('/payment/{reference}/upload', [PaymentController::class, 'showUploadBukti'])->name('payment.show-upload');
    Route::post('/payment/{reference}/upload', [PaymentController::class, 'uploadBukti'])->name('payment.upload-bukti');

    // Success page route
Route::get('/payment/success/{reference}', [PaymentController::class, 'showSuccess'])
     ->name('payment.success')
     ->middleware('auth');

// Halaman Awal dengan Animasi (PUBLIC - NO LOGIN REQUIRED)
Route::get('/halamanawal', function () {
    return view('pages.halamanawal');
})->name('halamanawal');

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Route untuk authenticated users
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // ==================== PREMIUM ROUTES ====================
    Route::prefix('premium')->group(function () {
        Route::get('/', [PremiumController::class, 'show'])->name('premium.show');
        Route::get('/check', [PremiumController::class, 'checkPremiumStatus'])->name('premium.check');
        Route::get('/upgrade', [PremiumController::class, 'showUpgradePage'])->name('premium.upgrade');
        Route::post('/upgrade', [PremiumController::class, 'upgradePlan'])->name('premium.upgrade.post');
        Route::get('/payment/{plan_id}', [PaymentPlanController::class, 'showPaymentPage'])->name('premium.payment');
        Route::post('/payment', [PaymentPlanController::class, 'createPlanPayment'])->name('premium.payment.create');
        Route::get('/payment/status/{reference}', [PaymentPlanController::class, 'paymentStatus'])->name('premium.payment.status');
        Route::post('/can-access', [PremiumController::class, 'canAccess'])->name('premium.can-access');
    });

    Route::get('/calista', [ModuleController::class, 'index'])->name('calista.index');

    Route::get('/calista/{slug}', function (Request $request, $slug) {
        // Ambil module lebih awal untuk memutuskan routing berdasarkan tipe
        $module = \App\Models\Module::where('slug', $slug)->first();

        // MODIFIED: Prioritas 1 - Selalu tampilkan module detail (level list)
        if ($module && !in_array($module->type, ['book', 'buku'])) {
            return app()->make(\App\Http\Controllers\LevelController::class)->showModule($slug);
        }

        // Jika module tipe buku -> buka halaman buku sesuai module
        if ($module && in_array($module->type, ['book', 'buku'])) {
            return app()->make(\App\Http\Controllers\BookController::class)->byModule($slug);
        }

        // Jika ada level_id di query, coba redirect ke puzzle pada level tersebut
        if ($levelId = $request->query('level_id')) {
            if ($module) {
                $level = \App\Models\Level::where('id', $levelId)
                    ->where('module_id', $module->id)
                    ->first();
                if ($level) {
                    $puzzle = \App\Models\PuzzleItem::where('level_id', $level->id)
                        ->where('is_active', true)
                        ->first();
                    if ($puzzle) {
                        return redirect()->route('calista.level.puzzle', ['slug' => $slug, 'level' => $level->id]);
                    }
                }
            }
        }

        if ($request->query('open') === 'puzzle' || $request->query('game') === 'puzzle') {
            if ($module) {
                $level = \App\Models\Level::where('module_id', $module->id)
                    ->whereHas('puzzleItems', function ($q) {
                        $q->where('is_active', true);
                    })
                    ->orderBy('order_number')
                    ->first();
                if ($level) {
                    return redirect()->route('calista.level.puzzle', ['slug' => $slug, 'level' => $level->id]);
                }
            }
        }

        // Redirect ke menulis AI jika module tipe writing
        if ($module && in_array($module->type, ['writing', 'menulis'])) {
            return redirect()->route('menulis-ai.interactive', ['slug' => $slug]);
        }

        return app()->make(ModuleController::class)->show($slug);
    })->name('calista.show');
    
    // Route untuk level
    Route::get('/calista/{slug}/level/{level}', [LevelController::class, 'show'])
         ->name('calista.level.show');
    
    // Route untuk complete level
    Route::post('/calista/{slug}/level/{level}/complete', [LevelController::class, 'complete'])
         ->name('calista.level.complete');
    
    // Route untuk generate audio pujian
    Route::post('/calista/{slug}/level/{level}/counting/success-audio', 
                [LevelController::class, 'generateSuccessAudio'])
         ->name('calista.level.counting.success-audio');

    // Route untuk halaman menulis
    Route::get('/calista/{slug}/level/{level}/writing/{writingItem}', [LevelController::class, 'showWriting'])
         ->name('calista.level.writing');

    // Route untuk halaman counting
    Route::get('/calista/{slug}/level/{level}/counting', [LevelController::class, 'showCounting'])
         ->name('calista.level.counting');

    // Route untuk halaman puzzle
    Route::get('/calista/{slug}/level/{level}/puzzle', [LevelController::class, 'showPuzzle'])
         ->name('calista.level.puzzle');
    
    Route::post('/calista/{slug}/level/{level}/puzzle/submit', [LevelController::class, 'submitPuzzle'])
         ->name('calista.level.puzzle.submit');

    // Typecast routes
    Route::prefix('typecast')->group(function () {
        Route::post('/greeting', [TypecastController::class, 'generateWritingGreeting']);
        Route::post('/custom', [TypecastController::class, 'customTTS'])->name('typecast.custom');
        Route::get('/test', [TypecastController::class, 'simpleTest']);
        Route::get('/raw-test', [TypecastController::class, 'rawTest']);
        Route::post('/encouragement', [TypecastController::class, 'generateEncouragement'])
             ->name('typecast.encouragement');
    });

    // ==================== MENULIS AI ROUTES ====================
    Route::prefix('menulis-ai')->group(function () {
        // Halaman utama menulis AI
        Route::get('/', [MenulisAIController::class, 'index'])
            ->name('menulis-ai.index');
        
        // Halaman menulis interaktif dengan AI
        Route::get('/interaktif/{slug}', [MenulisAIController::class, 'showInteractiveWriting'])
            ->name('menulis-ai.interactive');
        
        // Audio Generation Routes
        Route::post('/greeting', [MenulisAIController::class, 'generateGreeting'])
            ->name('menulis-ai.greeting');
        
        Route::post('/instruction', [MenulisAIController::class, 'generateInstruction'])
            ->name('menulis-ai.instruction');
        
        Route::post('/praise', [MenulisAIController::class, 'generatePraise'])
            ->name('menulis-ai.praise');
        
        Route::post('/correction', [MenulisAIController::class, 'generateCorrection'])
            ->name('menulis-ai.correction');
        
        Route::post('/story', [MenulisAIController::class, 'generateStoryForWriting'])
            ->name('menulis-ai.story');
        
        // Voice Interaction
        Route::post('/voice-interaction', [MenulisAIController::class, 'voiceInteraction'])
            ->name('menulis-ai.voice-interaction');
        
        // Progress Management
        Route::post('/save-progress', [MenulisAIController::class, 'saveProgress'])
            ->name('menulis-ai.save-progress');
        
        Route::get('/get-progress', [MenulisAIController::class, 'getProgress'])
            ->name('menulis-ai.get-progress');
        
        // Exercise Generation
        Route::post('/generate-exercise', [MenulisAIController::class, 'generateExercise'])
            ->name('menulis-ai.generate-exercise');
        
        // Test & Debug
        Route::get('/test-connection', [MenulisAIController::class, 'testConnection'])
            ->name('menulis-ai.test-connection');
        
        Route::delete('/clear-cache', [MenulisAIController::class, 'clearAudioCache'])
            ->name('menulis-ai.clear-cache');
        
        // Real-time TTS (untuk frontend)
        Route::post('/real-time-tts', function (Request $request) {
            $text = $request->text;
            $character = $request->character ?? 'default';
            
            // Gunakan controller untuk generate TTS
            return app()->make(MenulisAIController::class)->generateGreeting(new Request([
                'character' => $character,
                'writing_topic' => $text
            ]));
        })->name('menulis-ai.real-time-tts');
    });

    // BookPage Routes
    Route::prefix('book-page')->group(function () {
        Route::get('/{slug}/{pageNumber?}', [PageBookController::class, 'show'])
            ->name('book-page.show')
            ->where('pageNumber', '[0-9]+');
        
        Route::get('/{slug}/page/{page}/play-letters', [PageBookController::class, 'playAudioByLetters'])
            ->name('book-page.play-letters');
        
        Route::get('/{slug}/next/{currentPage}', [PageBookController::class, 'nextPage'])
            ->name('book-page.next');
        
        Route::get('/{slug}/prev/{currentPage}', [PageBookController::class, 'prevPage'])
            ->name('book-page.prev');
    });
 
    // Route redirect berdasarkan module
    Route::get('/calista/{slug}/counting', [LevelController::class, 'redirectToCounting'])
         ->name('calista.module.counting');
    
    Route::get('/calista/{slug}/puzzle', [LevelController::class, 'redirectToPuzzle'])
         ->name('calista.module.puzzle');
    
    // Route untuk halaman permainan - dengan cek status anak aktif dan real-time timer
    Route::get('/permainan', [AnakController::class, 'showPermainan'])->name('permainan');
    
    // ==================== GAME ROUTES ====================
  // Route untuk game gelas
Route::get('/game/{game}/gelas', [GameController::class, 'showGlassGame'])->name('game.gelas');
Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');

// API untuk menyimpan hadiah yang dipilih
Route::post('/game/{game}/save-selected-prizes', [GameController::class, 'saveSelectedPrizes'])->name('game.save-selected-prizes');

// API untuk mendapatkan hadiah (SEMUA YG DIPILIH)
Route::post('/game/{game}/get-prizes', [GameController::class, 'getSelectedPrizes'])->name('game.get-prizes');
    
    // Route untuk halaman selesai/completion
    Route::get('/selesai', [AnakController::class, 'showSelesai'])->name('selesai');
    
    Route::get('/permainan/matematika', function () {
        return "Halaman Permainan Matematika (Dalam Pengembangan)";
    })->name('permainan.matematika');

    Route::get('/permainan/huruf', function () {
        return "Halaman Permainan Huruf (Dalam Pengembangan)";
    })->name('permainan.huruf');

    Route::get('/permainan/warna', function () {
        return "Halaman Permainan Warna (Dalam Pengembangan)";
    })->name('permainan.warna');
    
    Route::get('/materi', function () {
        return view('pages.materi');
    })->name('materi');
    
    Route::get('/papan-peringkat', function () {
        return view('pages.papan-peringkat');
    })->name('papan-peringkat');

    // Buku Membaca Routes
    Route::prefix('buku-membaca')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('buku-membaca.index');
        Route::get('/module/{moduleSlug}', [BookController::class, 'byModule'])->name('buku-membaca.by-module');
        Route::get('/{slug}', [BookController::class, 'show'])->name('buku-membaca.show');
        Route::get('/{slug}/baca/{pageNumber?}', [BookController::class, 'read'])
            ->name('buku-membaca.read')
            ->where('pageNumber', '[0-9]+');
    });

    // Alias route for book detail (for backward compatibility)
    Route::get('/books/{slug}', [BookController::class, 'show'])->name('books.show');

    // ==================== CERITA RAKYAT AI ROUTES ====================
    Route::prefix('cerita-ai')->group(function () {
        // Halaman cerita interaktif dengan AI
        Route::get('/interaktif/{slug}', [CeritaRakyatAIController::class, 'showInteractiveStory'])
            ->name('cerita-ai.interaktif');
        
        // API untuk TTS real-time
        Route::post('/question-tts', [CeritaRakyatAIController::class, 'generateQuestionTTS'])
            ->name('cerita-ai.question-tts');
        
        Route::post('/choice-tts', [CeritaRakyatAIController::class, 'generateChoiceTTS'])
            ->name('cerita-ai.choice-tts');
        
        // Voice agent interaction
        Route::post('/voice-interaction', [CeritaRakyatAIController::class, 'processVoiceInteraction'])
            ->name('cerita-ai.voice-interaction');
        
        // Navigasi halaman
        Route::post('/{slug}/next-page', [CeritaRakyatAIController::class, 'getNextPage'])
            ->name('cerita-ai.next-page');
        
        // Images API
        Route::get('/{slug}/images/{pageNumber}', [CeritaRakyatAIController::class, 'getStoryImages'])
            ->name('cerita-ai.images');
        
        // Progress management
        Route::get('/{slug}/progress', [CeritaRakyatAIController::class, 'getProgress'])
            ->name('cerita-ai.progress');
        
        Route::post('/{slug}/complete', [CeritaRakyatAIController::class, 'completeStory'])
            ->name('cerita-ai.complete');
        
        // Audio generation
        Route::post('/{slug}/generate-audio', [CeritaRakyatAIController::class, 'generatePageAudio'])
            ->name('cerita-ai.generate-audio');
        
        // Cache management
        Route::delete('/{slug}/clear-cache', [CeritaRakyatAIController::class, 'clearAudioCache'])
            ->name('cerita-ai.clear-cache');
        
        // Test connection
        Route::get('/test-connection', [CeritaRakyatAIController::class, 'testPythonConnection'])
            ->name('cerita-ai.test-connection');
        
        // ========== NEW AUDIO MANAGEMENT ROUTES ==========
        Route::get('/{slug}/page/{pageNumber}/details', [CeritaRakyatAIController::class, 'getPageDetails'])
            ->name('cerita-ai.page-details');
        
        Route::get('/{slug}/page/{pageNumber}/audio', [CeritaRakyatAIController::class, 'playPageAudio'])
            ->name('cerita-ai.page-audio');
        
        Route::post('/{slug}/save-audio', [CeritaRakyatAIController::class, 'savePageAudio'])
            ->name('cerita-ai.save-audio');
        
        Route::post('/play-sequence', [CeritaRakyatAIController::class, 'playAudioSequence'])
            ->name('cerita-ai.play-sequence');
    });

    // Routes untuk Cerita Rakyat (kompatibilitas dengan route lama)
    Route::prefix('cerita')->group(function () {
        // Detail cerita rakyat dengan animasi (PRIMARY) - redirect ke AI version
        Route::get('/interaktif/{slug}', function ($slug) {
            return redirect()->route('cerita-ai.interaktif', ['slug' => $slug]);
        })->name('cerita.interaktif');
        
        // API untuk mendapatkan gambar
        Route::get('/{slug}/images', [BookController::class, 'getStoryImages'])
            ->name('cerita.images');
        
        // Cerita versi lama (untuk kompatibilitas)
        Route::get('/{slug}', [BookController::class, 'showCerita'])
            ->name('cerita.show');
        
        Route::get('/{slug}/baca/{pageNumber?}', [BookController::class, 'readCerita'])
            ->name('cerita.read')
            ->where('pageNumber', '[0-9]+');
    });

    // Progress management routes
    Route::prefix('progres')->group(function () {
        Route::post('/writing/save', [ProgresAnakController::class, 'saveWritingProgress'])
            ->name('progres.writing.save');
        
        Route::get('/writing/get', [ProgresAnakController::class, 'getProgress'])
            ->name('progres.writing.get');
        
        Route::post('/writing/continue', [ProgresAnakController::class, 'continueToNext'])
            ->name('progres.writing.continue');
    });

    // ==================== ANAK ROUTES DENGAN TIMER ====================
    Route::prefix('anak')->group(function () {
        Route::get('/create', [AnakController::class, 'create'])->name('anak.create');
        Route::post('/store', [AnakController::class, 'store'])->name('anak.store');
        Route::get('/profile', [AnakController::class, 'profile'])->name('anak.profile');
        Route::get('/daataanak', [AnakController::class, 'index'])->name('daataanak');
        Route::get('/grafik-progres', [ProgresAnakController::class, 'showProgressGraphics'])->name('anak.progress-graphics');
        Route::get('/check-active-status', [AnakController::class, 'checkActiveChildStatus'])->name('anak.check-active');
        
        // Timer management routes
        Route::post('/{id}/set-active', [AnakController::class, 'setActive'])->name('anak.set-active');
        Route::post('/{id}/update-limit', [AnakController::class, 'updateLimit'])->name('anak.update-limit');
        Route::get('/{id}/remaining-time', [AnakController::class, 'getRemainingTime'])->name('anak.remaining-time');
        Route::post('/{id}/start-timer', [AnakController::class, 'startTimer'])->name('anak.start-timer');
        Route::post('/{id}/stop-timer', [AnakController::class, 'stopTimer'])->name('anak.stop-timer');
        Route::post('/{id}/reset-timer', [AnakController::class, 'resetTimer'])->name('anak.reset-timer');
        Route::post('/{id}/deduct-time', [AnakController::class, 'deductTime'])->name('anak.deduct-time');
    });

    // ==================== VOICE AGENT ROUTES ====================
Route::prefix('voice-agent')->group(function () {
    // Halaman utama voice agent
    Route::get('/', [VoiceAgentController::class, 'index'])
        ->name('voice-agent.index');
    
    // Voice chat interaktif
    Route::get('/chat/{slug?}', [VoiceAgentController::class, 'voiceChat'])
        ->name('voice-agent.chat');
    
    // API Endpoints
    Route::post('/process-voice', [VoiceAgentController::class, 'processVoice'])
        ->name('voice-agent.process-voice');
    
    Route::post('/text-to-speech', [VoiceAgentController::class, 'textToSpeech'])
        ->name('voice-agent.text-to-speech');
    
    Route::post('/speech-to-text', [VoiceAgentController::class, 'speechToText'])
        ->name('voice-agent.speech-to-text');
    
    Route::post('/text-chat', [VoiceAgentController::class, 'textChat'])
        ->name('voice-agent.text-chat');
    
    Route::get('/health', [VoiceAgentController::class, 'checkServerHealth'])
        ->name('voice-agent.health');
    
    Route::get('/history', [VoiceAgentController::class, 'getHistory'])
        ->name('voice-agent.history');
    
    Route::delete('/clear-history', [VoiceAgentController::class, 'clearHistory'])
        ->name('voice-agent.clear-history');
    
    Route::post('/learning-session', [VoiceAgentController::class, 'learningSession'])
        ->name('voice-agent.learning-session');
    
    Route::get('/test-audio', [VoiceAgentController::class, 'testAudio'])
        ->name('voice-agent.test-audio');
});

// ==================== TAMBAHAN: API untuk progress ====================
    Route::get('/calista/{slug}/progress', [LevelController::class, 'getProgress'])
        ->name('calista.module.progress');
    
    // Route::get('/nusa', [NusaController::class, 'index'])->name('voice-agent.index');

});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\ParentalGateController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\MoodController; // 🆕 Mood Tracking
use App\Http\Controllers\Api\StoryApiController;

// Public routes (tidak perlu autentikasi)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/google', [AuthController::class, 'googleLogin']);
    Route::get('/check', [AuthController::class, 'checkAuth']); // Check auth status
    
    // Email Verification & Password Reset OTP routes
    Route::post('/verify-email', [AuthController::class, 'verifyEmailOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::post('/payments/louvin/webhook', [SubscriptionController::class, 'louvinWebhook']);

// Public Module routes untuk Flutter
Route::prefix('modules')->group(function () {
    Route::get('/', [ModuleController::class, 'index']);
    Route::get('/{id}', [ModuleController::class, 'show']);
    Route::get('/{id}/levels', [ModuleController::class, 'levels']); // Levels per module
    Route::get('/{moduleId}/levels/{levelId}/items', [ModuleController::class, 'levelItems']); // 🆕 Soal per level
});

Route::get('/stories', [StoryApiController::class, 'index']);
Route::get('/stories/{slug}', [StoryApiController::class, 'show']);
Route::get('/stories/{slug}/reviews', [StoryApiController::class, 'getReviews']);

// Protected routes (perlu autentikasi via token)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Sub-group requiring verified email
    Route::middleware('verified')->group(function () {
        // Protected Module routes untuk Flutter (hanya admin)
        Route::prefix('modules')->group(function () {
            Route::post('/', [ModuleController::class, 'store']);
            Route::put('/{id}', [ModuleController::class, 'update']);
            Route::delete('/{id}', [ModuleController::class, 'destroy']);
        });

        // 🎓 Children API — CRUD profil anak + timer
        Route::prefix('children')->group(function () {
            Route::get('/', [ChildController::class, 'index']);       // Daftar semua anak
            Route::post('/', [ChildController::class, 'store']);       // Tambah anak baru
            Route::get('/{id}', [ChildController::class, 'show']);     // Detail anak + progres
            Route::put('/{id}', [ChildController::class, 'update']);   // Edit profil anak
            Route::delete('/{id}', [ChildController::class, 'destroy']); // Hapus anak

            // Timer endpoints
            Route::post('/{id}/timer/start', [ChildController::class, 'startTimer']);  // Mulai timer
            Route::post('/{id}/timer/stop', [ChildController::class, 'stopTimer']);    // Stop timer
            Route::get('/{id}/timer', [ChildController::class, 'timerStatus']);        // Cek status timer
            Route::post('/{id}/timer/verify-pin', [ChildController::class, 'verifyPin']); // 🆕 Verifikasi PIN unlock

            // Audio Pack Offline pre-generation endpoints
            Route::post('/{id}/audio-pack/generate', [ChildController::class, 'generateAudioPack']);
            Route::get('/{id}/audio-pack/status', [ChildController::class, 'audioPackStatus']);
        });

        // Smart Audio Pack Routes
        Route::prefix('audio-pack')->group(function () {
            Route::get('/manifest', [\App\Http\Controllers\Api\AudioPackController::class, 'getManifest']);
            Route::get('/name', [\App\Http\Controllers\Api\AudioPackController::class, 'getNameAudio']);
        });

        // 📊 Progress API — Simpan & laporan progres belajar
        Route::prefix('progress')->group(function () {
            Route::post('/', [ProgressController::class, 'store']);          // 🆕 Simpan hasil belajar
            Route::get('/{child_id}/sessions', [ProgressController::class, 'sessionReport']); // Laporan sesi mingguan/bulanan
            Route::get('/{child_id}/sessions/export', [ProgressController::class, 'exportSessions']); // Download CSV laporan sesi
            Route::get('/{child_id}/report-pdf', [ProgressController::class, 'exportPdf']); // 📄 Download PDF laporan lengkap
            Route::post('/{child_id}/report-email', [ProgressController::class, 'sendReportEmail']); // 📧 Kirim email laporan lengkap
            Route::get('/{child_id}', [ProgressController::class, 'report']); // 🆕 Laporan progres anak
            Route::get('/{child_id}/history', [ProgressController::class, 'history']); // 🆕 Riwayat lengkap (paginated)
        });

        // 👗 Shop API — Toko baju & inventory Nusa (Model Hybrid)
        Route::prefix('shop')->group(function () {
            Route::get('/', [ShopController::class, 'items']);           // Daftar semua baju + status
            Route::post('/claim', [ShopController::class, 'claimReward']); // Claim baju reward
            Route::get('/inventory', [ShopController::class, 'inventory']); // Lemari baju anak
            Route::post('/equip', [ShopController::class, 'equip']);     // Ganti baju Nusa
            Route::post('/buy-premium', [ShopController::class, 'buyPremium']); // 🆕 Beli baju premium (subscriber only)
        });

        // 💳 Subscription API — Aktivasi & Cek Status Premium
        Route::prefix('subscription')->group(function () {
            Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
            Route::get('/status', [SubscriptionController::class, 'status']);
        });

        // 🔐 Parental Gate API — Double Layer Security untuk pembelian
        Route::prefix('parental-gate')->group(function () {
            Route::post('/challenge', [ParentalGateController::class, 'generateChallenge']);       // Layer 1: Generate angka-dari-kata
            Route::post('/verify', [ParentalGateController::class, 'verifyChallenge']);             // Layer 1: Cek jawaban
            Route::get('/pin-status', [ParentalGateController::class, 'pinStatus']);
            Route::post('/setup-pin', [ParentalGateController::class, 'setupPin']);
            Route::post('/verify-password', [ParentalGateController::class, 'verifyPassword']);     // Layer 2: Cek password orang tua
        });

        // 😊 Mood Tracking API — Check-in emosi harian anak (1x/hari)
        Route::prefix('moods')->group(function () {
            Route::post('/', [MoodController::class, 'store']);                              // Simpan mood hari ini
            Route::get('/status/{child_id}', [MoodController::class, 'checkTodayStatus']); // Cek apakah sudah check-in hari ini
            Route::get('/report/{child_id}', [MoodController::class, 'report']);            // Laporan 7 hari (Parent Dashboard)
        });

        // 📚 Dongeng Nusa API — Cerita Rakyat Interaktif (Unified Media Playback)
        Route::prefix('stories')->group(function () {
            Route::post('/{slug}/progress', [StoryApiController::class, 'saveProgress']);
            Route::post('/{slug}/like', [StoryApiController::class, 'toggleLike']);
            Route::post('/{slug}/reviews', [StoryApiController::class, 'submitReview']);
        });

        // 🎙️ Voice Agent API — Integrasi Nusa AI
        Route::prefix('voice')->group(function () {
            Route::post('/process', [\App\Http\Controllers\VoiceAgentController::class, 'processVoice']);
            Route::post('/reading-assess', [\App\Http\Controllers\VoiceAgentController::class, 'assessReading']);
            Route::post('/tts', [\App\Http\Controllers\VoiceAgentController::class, 'textToSpeech']);
            Route::post('/stt', [\App\Http\Controllers\VoiceAgentController::class, 'speechToText']);
            Route::post('/chat', [\App\Http\Controllers\VoiceAgentController::class, 'textChat']);
            Route::get('/credits', [\App\Http\Controllers\VoiceAgentController::class, 'creditStatus']);
            Route::get('/history', [\App\Http\Controllers\VoiceAgentController::class, 'getHistory']);
            Route::delete('/history', [\App\Http\Controllers\VoiceAgentController::class, 'clearHistory']);
        });
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BridgeController;

Route::get('/', function () {
    return view('welcome');
});

// ==================== BRIDGE ROUTES (MOBILE TO WEB) ====================
Route::get('/bridge/login', [BridgeController::class, 'loginWithToken'])->name('bridge.login');

Route::get('/animated-character', function () {
    return view('animated-character');
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ModuleController;

// Public routes (tidak perlu autentikasi)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/check', [AuthController::class, 'checkAuth']); // Check auth status
});

// Public Module routes untuk Flutter
Route::prefix('modules')->group(function () {
    Route::get('/', [ModuleController::class, 'index']);
    Route::get('/{id}', [ModuleController::class, 'show']);
});

// Protected routes (perlu autentikasi via token)
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Protected Module routes untuk Flutter (hanya admin)
    Route::prefix('modules')->group(function () {
        Route::post('/', [ModuleController::class, 'store']);
        Route::put('/{id}', [ModuleController::class, 'update']);
        Route::delete('/{id}', [ModuleController::class, 'destroy']);
    });
});

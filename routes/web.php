<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BridgeController;

Route::get('/', function () {
    return view('welcome');
});



// ==================== BRIDGE ROUTES (MOBILE TO WEB) ====================
Route::get('/bridge/login', [BridgeController::class, 'loginWithToken'])->name('bridge.login');

// ==================== ADMIN PDF REPORT (Filament) ====================
Route::middleware(['auth:web'])->group(function () {
    Route::get('/admin/anak/{childId}/laporan-pdf', [\App\Http\Controllers\Admin\AdminReportController::class, 'downloadPdf'])
        ->name('admin.anak.pdf');
});

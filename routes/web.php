<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PimpinanSuratController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffSuratController;
use App\Http\Controllers\SuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1']);
Route::get('/daftar', [AuthController::class, 'showRegister'])->middleware('guest');
Route::post('/daftar', [AuthController::class, 'register'])->middleware('guest');
Route::view('/lupa-password', 'lupa-password')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Pintu masuk setelah login: arahkan sesuai role
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'pengirim' => redirect('/pengirim/dashboard'),
        'staff' => redirect('/staff/dashboard'),
        'pimpinan' => redirect('/pimpinan/dashboard'),
        default => abort(403),
    };
})->middleware('auth');

// Pengaturan akun (semua peran yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/pengaturan', [SettingController::class, 'index']);
    Route::post('/pengaturan/profil', [SettingController::class, 'updateProfil']);
    Route::post('/pengaturan/password', [SettingController::class, 'updatePassword']);
});

// Pengirim
Route::middleware(['auth', 'role:pengirim'])->group(function () {
    Route::get('/pengirim/dashboard', [DashboardController::class, 'pengirim']);

    Route::get('/pengirim/surat', [SuratController::class, 'index']);
    Route::get('/pengirim/surat/baru', [SuratController::class, 'create']);
    Route::get('/pengirim/surat/{surat}', [SuratController::class, 'show']);
    Route::post('/pengirim/surat', [SuratController::class, 'store']);
});

// Staff
Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/staff/dashboard', [DashboardController::class, 'staff']);

    Route::get('/staff/surat', [StaffSuratController::class, 'index']);
    Route::get('/staff/surat/{surat}', [StaffSuratController::class, 'show']);
    Route::post('/staff/surat/{surat}/status', [StaffSuratController::class, 'updateStatus']);
    Route::get('/staff/arsip', [ArsipController::class, 'index']);
});

// Pimpinan
Route::middleware(['auth', 'role:pimpinan'])->group(function () {
    Route::get('/pimpinan/dashboard', [DashboardController::class, 'pimpinan']);

    Route::get('/pimpinan/surat', [PimpinanSuratController::class, 'index']);
    Route::get('/pimpinan/surat/{surat}', [PimpinanSuratController::class, 'show']);
    Route::post('/pimpinan/surat/{surat}/disposisi', [PimpinanSuratController::class, 'storeDisposisi']);
    Route::get('/pimpinan/arsip', [ArsipController::class, 'index']);
});
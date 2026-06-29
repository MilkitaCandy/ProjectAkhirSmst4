<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;

// 1. Arahkan domain utama langsung ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// 2. Route untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 3. Route untuk yang sudah Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // --- BISA DIAKSES ADMIN & USER (READ ONLY & LIVE SEARCH) ---
    Route::get('/dashboard', [AssetController::class, 'index'])->name('dashboard');
    Route::get('/assets/fetch', [AssetController::class, 'fetch']);

    // --- HANYA BISA DIAKSES ADMIN (CREATE, UPDATE, DELETE) ---
    Route::middleware('role:admin')->group(function () {
        Route::post('/assets', [AssetController::class, 'store']);
        Route::get('/assets/{id}/edit', [AssetController::class, 'edit']);
        Route::put('/assets/{id}', [AssetController::class, 'update']);
        Route::delete('/assets/{id}', [AssetController::class, 'destroy']);
    });
});
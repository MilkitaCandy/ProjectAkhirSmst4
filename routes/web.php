<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Jika user membuka halaman utama, arahkan ke login
Route::get('/', function () {
    return redirect('/login');
});

// Routes untuk Authentication (Hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes yang membutuhkan user login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Halaman Dashboard Sementara
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});
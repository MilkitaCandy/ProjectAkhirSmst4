<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes khusus yang sudah Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Halaman Dashboard Utama (Bisa diakses Admin & User)
    // Nanti kita akan arahkan ini ke view halaman dashboard full AJAX
    Route::get('/dashboard', function () {
        return "Ini halaman Dashboard. Login sebagai: " . auth()->user()->role;
    })->name('dashboard');

    // CONTOH PROTEKSI ROUTE KHUSUS ADMIN (Untuk proses CRUD Aset nanti)
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/test', function () {
            return "Halaman ini cuma bisa dibuka oleh Admin. Jika User buka, pasti Error 403!";
        });
    });
});
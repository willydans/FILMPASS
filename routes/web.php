<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT CONTROLLER ADMIN (Semua sekarang ada di namespace 'Admin')
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\FilmController; // <-- DIPINDAHKAN ke Admin

// 2. IMPORT CONTROLLER AUTH (Namespace yang Benar)
use App\Http\Controllers\Auth\AuthController; // <-- PERBAIKAN NAMESPACE

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Rute Publik (Guest) ===
Route::get('/', function () {
    return view('dashboard'); 
});

// Rute otentikasi pengguna
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout'); // Logout


// === Rute Pengguna (Harus Login) ===
// PERBAIKAN: Melindungi rute user dengan middleware 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/movies', function () {
        return view('movies');
    });

    Route::get('/user/riwayat', function () {
        return view('user.riwayat');
    })->name('riwayat');
});


// === GRUP ROUTE UNTUK ADMIN ===
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Auth Admin (Tamu Admin)
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // PERBAIKAN: Melindungi SEMUA rute admin dengan middleware 'auth'
    // (Nanti Anda bisa tambahkan pengecekan role di sini, misal: ['auth', 'role:admin'])
    Route::middleware(['auth'])->group(function () {
    
        // Rute Admin Utama
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Film (Sekarang menggunakan Admin\FilmController)
        Route::resource('/films', FilmController::class);
        
        // Manajemen Studio
        Route::resource('/studios', StudioController::class);
        Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])->name('studios.toggleStatus');

        // Manajemen Jadwal Tayang
        Route::resource('/schedules', ScheduleController::class);

        // Rute Fasilitas (Hanya untuk memproses modal)
        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    
    }); // Akhir dari grup middleware admin
    
});
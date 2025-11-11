<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT CONTROLLER PUBLIK (NON-ADMIN)
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\TicketController;

// 2. IMPORT SEMUA CONTROLLER ADMIN ANDA
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Rute Publik (Guest & User) ===
Route::get('/', function () {
    return view('dashboard'); 
});

// Rute Auth Pengguna (dari file AuthController)
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Rute yang memerlukan login user biasa
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

     Route::get('/user/detail', function () {
        return view('user.detail');
    })->name('detail');

    // Rute dari screenshot Anda
    Route::get('pesan-tiket/{title}', [TicketController::class, 'index'])->name('pesan.tiket');
    Route::post('pesan-tiket', [TicketController::class, 'store'])->name('pesan.tiket.store');
});


// === GRUP ROUTE UNTUK ADMIN ===
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Auth Admin (Tamu Admin)
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Rute Admin Utama (Dilindungi Middleware)
    // Semua rute di sini memerlukan login
    Route::middleware(['auth'])->group(function () {
    
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Film
        Route::resource('/films', FilmController::class);
        
        // Manajemen Studio
        Route::resource('/studios', StudioController::class);
        Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])->name('studios.toggleStatus');

        // Manajemen Jadwal Tayang
        Route::resource('/schedules', ScheduleController::class);

        // Rute Fasilitas (Hanya untuk modal)
        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    
    }); // Akhir dari grup middleware admin
    
});
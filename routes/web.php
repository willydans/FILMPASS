<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT SEMUA CONTROLLER ANDA
use App\Http\Controllers\FilmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Auth\AuthController; // Pastikan namespace ini benar
use App\Http\Controllers\Admin\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Rute Publik (Guest) ===
// Rute ini HANYA bisa diakses oleh user yang BELUM LOGIN.
// 'guest' adalah alias untuk middleware 'RedirectIfAuthenticated' yang baru kita edit.
Route::middleware(['guest'])->group(function () {
    
    Route::get('/', function () {
        return view('dashboard'); 
    });

    // Rute otentikasi pengguna
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

});


// === Rute Pengguna (Harus Login) ===
// Rute ini HANYA bisa diakses oleh user yang SUDAH LOGIN
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
    
    // Rute Logout (harus login untuk bisa logout)
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});


// === GRUP ROUTE UNTUK ADMIN ===
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Auth Admin (Hanya untuk Tamu)
    Route::middleware(['guest'])->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });
    
    // Rute Logout Admin (Harus login untuk bisa logout)
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    //
    // --- RUTE ADMIN UTAMA (DIAMANKAN) ---
    // User harus login DAN memiliki role 'admin'/'kasir' untuk mengakses ini
    // (Asumsi Anda sudah membuat middleware 'role.admin' dari langkah kita sebelumnya)
    //
    Route::middleware(['auth', 'role.admin'])->group(function () {
    
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('/films', FilmController::class);
        
        Route::resource('/studios', StudioController::class);
        Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])->name('studios.toggleStatus');

        Route::resource('/schedules', ScheduleController::class);

        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    
    }); // Akhir dari grup middleware 'auth' & 'role.admin'
    
});
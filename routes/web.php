<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT CONTROLLER ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\FilmController;

// 2. IMPORT CONTROLLER AUTH
use App\Http\Controllers\Auth\AuthController;

// 3. IMPORT CONTROLLER TICKET (UNTUK USER)
use App\Http\Controllers\TicketController; // <-- TAMBAHKAN INI

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Rute Publik (Guest) ===
Route::get('/', function () {
    return view('dashboard'); 
})->name('home');

// Rute otentikasi pengguna
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


// === Rute Pengguna (Harus Login) ===
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/movies', function () {
        return view('movies');
    })->name('movies');

    Route::get('/user/riwayat', function () {
        return view('user.riwayat');
    })->name('riwayat');

    // ✅ RUTE PEMESANAN TIKET (INI UNTUK USER, BUKAN ADMIN)
    Route::get('/pesan-tiket/{title}', [TicketController::class, 'show'])->name('pesan.tiket');
    Route::post('/pesan-tiket', [TicketController::class, 'store'])->name('pesan.tiket.store');
});


// === GRUP ROUTE UNTUK ADMIN ===
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Auth Admin (Guest Admin)
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Rute yang memerlukan autentikasi admin
    Route::middleware(['auth'])->group(function () {
    
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Film
        Route::resource('/films', FilmController::class);
        
        // Manajemen Studio
        Route::resource('/studios', StudioController::class);
        Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])->name('studios.toggleStatus');

        // Manajemen Jadwal Tayang
        Route::resource('/schedules', ScheduleController::class);

        // Rute Fasilitas
        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    
    }); // Akhir grup middleware admin
    
});
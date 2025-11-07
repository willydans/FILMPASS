<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT SEMUA CONTROLLER ANDA (PASTIKAN TIDAK ADA YANG DUPLIKAT)
use App\Http\Controllers\FilmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController; // <-- HANYA SATU KALI
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\Admin\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Rute Publik (Guest & User) ===
Route::get('/', function () {
    return view('dashboard'); 
});
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/movies', function () {
    return view('movies');
});
// Halaman Riwayat Pesanan (hanya untuk user login)
Route::get('/user/riwayat', function () {
    return view('user.riwayat');
})->middleware('auth')->name('riwayat');


// === GRUP ROUTE UNTUK ADMIN ===
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Auth Admin
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Rute Admin Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/films', FilmController::class);
    
    // Manajemen Studio
    Route::resource('/studios', StudioController::class);
    
    //
    // --- PENYESUAIAN DI SINI ---
    // Menambahkan rute kustom untuk tombol "Maintenance" / "Aktifkan"
    Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])->name('studios.toggleStatus');
    //
    //

    // Manajemen Jadwal Tayang
    Route::resource('/schedules', ScheduleController::class);

    
    // Rute Fasilitas (HANYA SATU KALI)
    Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    
});
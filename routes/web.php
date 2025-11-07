<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT SEMUA CONTROLLER ANDA
use App\Http\Controllers\FilmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk Halaman Depan (Homepage)
Route::get('/', function () {
    return view('dashboard'); 
});


// 3. GRUP ROUTE UNTUK ADMIN
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    // URL: /admin/dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Film
    // URL: /admin/films, /admin/films/create, dll.
    //
    // === PERBAIKAN DI SINI ===
    // Menghapus titik ekstra (typo) dari 'Route.::resource'
    //
    Route::resource('/films', FilmController::class);

    // Manajemen Studio
    // URL: /admin/studios, /admin/studios/create, dll.
    // (Ini sudah benar menggunakan '/studios' plural)
    Route::resource('/studios', StudioController::class);

    // Manajemen Jadwal Tayang
    // URL: /admin/schedules, /admin/schedules/create, dll.
    Route::resource('/schedules', ScheduleController::class);

});
<?php

use Illuminate\Support\Facades\Route;
// 1. IMPORT CONTROLLER YANG SUDAH ADA
use App\Http\Controllers\FilmController;
// 2. TAMBAHKAN IMPORT BARU UNTUK DASHBOARD ADMIN
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute untuk Halaman Depan (Homepage)
Route::get('/', function () {
    return view('dashboard'); 
});


// 3. GRUP RUTE UNTUK ADMIN
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Rute untuk Dashboard Admin (INI YANG BARU)
    // URL: /admin/dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Rute untuk Manajemen Film ---
    // Cara ini lebih ringkas daripada mendaftarkannya satu per satu.
    // Ini akan secara otomatis membuat rute untuk:
    // - films.index   (GET /admin/films)
    // - films.create  (GET /admin/films/create)
    // - films.store   (POST /admin/films)
    // - films.show    (GET /admin/films/{film})
    // - films.edit    (GET /admin/films/{film}/edit)
    // - films.update  (PUT/PATCH /admin/films/{film})
    // - films.destroy (DELETE /admin/films/{film})
    Route::resource('/films', FilmController::class);

    
    // --- Anda bisa menambahkan rute lain di sini (sebagai placeholder) ---
    
    // Nanti, saat Anda membuat StudioController, Anda bisa menambahkannya seperti ini:
    // Route::resource('/studios', StudioController::class);

    // Nanti, saat Anda membuat ScheduleController, Anda bisa menambahkannya seperti ini:
    // Route::resource('/schedules', ScheduleController::class);

    // Dst...

});
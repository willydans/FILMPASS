<?php

use Illuminate\Support\Facades\Route;

// IMPORT CONTROLLER
use App\Http\Controllers\FilmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard'); 
});

// Rute untuk otentikasi pengguna
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Rute untuk dashboard pengguna
Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/movies', function () {
    return view('movies');
});

// GRUP ROUTE UNTUK ADMIN
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute untuk login admin
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Film
    Route::resource('/films', FilmController::class);

    // Manajemen Studio (resource-style)
    Route::resource('/studio', StudioController::class);

});

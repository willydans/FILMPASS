<?php

use Illuminate\Support\Facades\Route;

// IMPORT CONTROLLER
use App\Http\Controllers\FilmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard'); 
});

// GRUP ROUTE UNTUK ADMIN
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Film
    Route::resource('/films', FilmController::class);

    // Manajemen Studio (resource-style)
    Route::resource('/studio', StudioController::class);

});

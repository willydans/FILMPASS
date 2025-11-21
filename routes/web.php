<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT SEMUA CONTROLLER
use App\Http\Controllers\FilmController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\TicketController; // <-- TAMBAHAN BARU (PENTING!)
use App\Http\Controllers\Auth\AuthController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Rute Publik (Guest) ===
// Hanya bisa diakses oleh user yang BELUM LOGIN
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
// Hanya bisa diakses oleh user yang SUDAH LOGIN (role 'user' biasa)
Route::middleware(['auth'])->group(function () {
    
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- FITUR FILM & PEMESANAN TIKET (BARU) ---
    
    // 1. Daftar Semua Film (Dinamis dari DB)
    Route::get('/movies', [TicketController::class, 'index'])->name('movies.index');

    // 2. Form Pesan Tiket (Pilih Jadwal)
    Route::get('/pesan-tiket/{film}', [TicketController::class, 'create'])->name('ticket.create');

    // 3. Proses Simpan Pemesanan (Checkout)
    Route::post('/pesan-tiket', [TicketController::class, 'store'])->name('ticket.store');


    // --- FITUR RIWAYAT & DETAIL TIKET ---
    
    // 1. Menampilkan daftar riwayat
    Route::get('/user/riwayat', [HistoryController::class, 'index'])->name('riwayat');

    // 2. Menampilkan detail tiket spesifik (E-Ticket)
    Route::get('/user/riwayat/{id}', [HistoryController::class, 'show'])->name('riwayat.detail');

    // Rute Logout
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
    // User harus login DAN memiliki role 'admin'/'kasir'
    //
    Route::middleware(['auth', 'role.admin'])->group(function () {
    
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('/films', FilmController::class); // Admin Film Controller (beda dengan user)
        
        Route::resource('/studios', StudioController::class);
        // Rute khusus untuk tombol Maintenance/Aktifkan
        Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])->name('studios.toggleStatus');

        Route::resource('/schedules', ScheduleController::class);

        // Rute Fasilitas (Hanya untuk modal)
        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

          // Manajemen Pemesanan (BARU)
        Route::get('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [App\Http\Controllers\Admin\BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
        Route::delete('/bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('bookings.destroy');

        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-excel', [App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export-pdf', [App\Http\Controllers\Admin\ReportController::class, 'exportPDF'])->name('reports.export.pdf');
    
    }); 
    
});
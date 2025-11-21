<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT SEMUA CONTROLLER
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\TicketController; 
use App\Http\Controllers\Auth\AuthController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Rute Publik (Guest) ===
// Hanya bisa diakses oleh user yang BELUM LOGIN
Route::middleware(['guest'])->group(function () {
    
    Route::get('/', [UserDashboardController::class, 'index'])->name('home'); 

    // Rute otentikasi pengguna
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

});


// === Rute Pengguna (Harus Login) ===
// Hanya bisa diakses oleh user yang SUDAH LOGIN (role 'user' biasa)
Route::middleware(['auth'])->group(function () {
    
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // --- FITUR FILM & PEMESANAN TIKET ---
    
    // 1. Daftar Semua Film
    Route::get('/movies', [TicketController::class, 'index'])->name('movies.index');

    // 2. Detail Film & Pilih Jadwal
    Route::get('/movies/{film}', [TicketController::class, 'create'])->name('ticket.create');

    // 3. Pilih Kursi (Visual)
    Route::get('/booking/seats/{schedule}', [TicketController::class, 'selectSeats'])->name('booking.seats');

    // 4. Checkout & Pembayaran
    Route::post('/booking/checkout', [TicketController::class, 'showCheckout'])->name('booking.checkout');

    // 5. Proses Pembayaran
    Route::post('/booking/process', [TicketController::class, 'processPayment'])->name('booking.process');


    // --- FITUR RIWAYAT ---
    // URL dirapikan jadi /riwayat (tanpa /user di depannya)
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('riwayat');
    Route::get('/riwayat/{id}', [HistoryController::class, 'show'])->name('riwayat.detail');

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
    
    // Rute Logout Admin
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    //
    // --- RUTE ADMIN UTAMA ---
    //
    Route::middleware(['auth', 'role.admin'])->group(function () {
    
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('/films', FilmController::class);
        
        Route::resource('/studios', StudioController::class);
        Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])->name('studios.toggleStatus');

        Route::resource('/schedules', ScheduleController::class);

        // Rute Fasilitas
        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

        // Manajemen Pemesanan
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

        // Laporan
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export.pdf');
    
    }); 
    
});
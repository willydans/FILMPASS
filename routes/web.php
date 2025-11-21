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

    // Rute Login & Register Manual User
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // ==========================================
    // RUTE GOOGLE LOGIN (USER)
    // ==========================================
    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

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
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('riwayat');
    Route::get('/riwayat/{id}', [HistoryController::class, 'show'])->name('riwayat.detail');

    // Rute Logout User
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});


// === GRUP ROUTE UNTUK ADMIN ===
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Auth Admin (Hanya untuk Tamu)
    Route::middleware(['guest'])->group(function () {
        // Login Manual Admin
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);

        // ==========================================
        // RUTE GOOGLE LOGIN (ADMIN) - TAMBAHAN BARU
        // ==========================================
        // Ini menggunakan method yang kita tambahkan di AdminAuthController tadi
        Route::get('auth/google', [AdminAuthController::class, 'redirectToGoogle'])->name('google.login');
        // Catatan: Callback Google biasanya diarahkan ke satu URL utama.
        // Jika Anda menggunakan callback yang SAMA dengan user (auth/google/callback),
        // maka logika pemisahan (redirect ke dashboard admin vs user) harus ada di AuthController biasa.
        // Namun, jika Anda mendaftarkan URL callback KHUSUS admin di Google Console (misal: /admin/auth/google/callback),
        // maka gunakan baris di bawah ini:
        // Route::get('auth/google/callback', [AdminAuthController::class, 'handleGoogleCallback']);
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
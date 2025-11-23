<?php

use Illuminate\Support\Facades\Route;

// 1. IMPORT SEMUA CONTROLLER
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\TicketController; 
use App\Http\Controllers\MidtransController; 
use App\Http\Controllers\Auth\AuthController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\ScheduleController; 
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// RUTE PUBLIK (GUEST)
// =====================
Route::middleware(['guest'])->group(function () {
    
    Route::get('/', [UserDashboardController::class, 'index'])->name('home'); 

    // Login / Register User
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // Google Login (User)
    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});


// =====================
// RUTE KHUSUS MIDTRANS (WEBHOOK)
// =====================
Route::post('/midtrans-notification', [MidtransController::class, 'notification'])->name('midtrans.notification');


// =====================
// RUTE USER (HARUS LOGIN)
// =====================
Route::middleware(['auth'])->group(function () {
    
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // --- FITUR PEMESANAN TIKET ---
    
    // 1. Daftar Semua Film
    Route::get('/movies', [TicketController::class, 'index'])->name('movies.index');

    // 2. Detail Film & Pilih Jadwal
    Route::get('/movies/{film}', [TicketController::class, 'create'])->name('ticket.create');

    // 3. Pilih Kursi (Visual)
    Route::get('/booking/seats/{schedule}', [TicketController::class, 'selectSeats'])->name('booking.seats');

    // 4. Checkout & Pembayaran
    Route::get('/booking/checkout', function() {
        return redirect()->route('movies.index')->with('error', 'Silakan pilih jadwal dan kursi terlebih dahulu.');
    });
    Route::post('/booking/checkout', [TicketController::class, 'showCheckout'])->name('booking.checkout');

    // 5. Proses Pembayaran (Simpan DB)
    Route::post('/booking/process', [TicketController::class, 'processPayment'])->name('booking.process');
    
    // 6. Callback Sukses (Update Status Instan dari Frontend)
    Route::post('/booking/payment-success', [TicketController::class, 'paymentSuccess'])->name('booking.success');

    // 7. Cek Status Manual (Fitur Pamungkas untuk Sandbox)
    // INI RUTE YANG HILANG SEBELUMNYA:
    Route::get('/booking/check-status/{booking}', [TicketController::class, 'checkStatus'])->name('booking.check_status');


    // --- FITUR RIWAYAT ---
    Route::get('/riwayat', [HistoryController::class, 'index'])->name('riwayat');
    Route::get('/riwayat/{id}', [HistoryController::class, 'show'])->name('riwayat.detail');
    
    // Rute Pembatalan Pesanan
    Route::post('/riwayat/{booking}/cancel', [HistoryController::class, 'cancelBooking'])->name('riwayat.cancel');

    // Rute Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});


// =====================
// RUTE ADMIN
// =====================
Route::prefix('admin')->name('admin.')->group(function () {

    // ---- Guest Admin (Belum login) ----
    Route::middleware(['guest'])->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
        
        // Google login admin
        Route::get('auth/google', [AdminAuthController::class, 'redirectToGoogle'])->name('google.login');
    });
    
    // ---- Logout Admin ----
    Route::post('logout', [AdminAuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    // ---- ADMIN LOGIN + ROLE VALID ----
    Route::middleware(['auth', 'role.admin'])->group(function () {
    
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('/films', FilmController::class);
        
        Route::resource('/studios', StudioController::class);
        Route::patch('/studios/{studio}/toggle-status', [StudioController::class, 'toggleStatus'])
            ->name('studios.toggleStatus');

        Route::resource('/schedules', ScheduleController::class);

        // Fasilitas
        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

        // Pemesanan
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

        // === Manajemen User ===
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    
    }); 
    
});
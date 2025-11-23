<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat pemesanan user (List).
     */
    public function index()
    {
        // Mendapatkan ID user yang sedang login
        $userId = Auth::id();

        $bookings = Booking::with(['schedule.film', 'schedule.studio'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Menggunakan 10 item per halaman

        // MENGARAHKAN KE resources/views/user/riwayat.blade.php
        return view('user.riwayat', compact('bookings')); 
    }

    /**
     * Menampilkan detail tiket spesifik (E-Ticket).
     * Menerima $id sebagai ID Booking.
     */
    public function show($id)
    {
        $currentUserId = Auth::id();

        // Cari Booking berdasarkan ID dan USER ID untuk memverifikasi kepemilikan
        $booking = Booking::with(['schedule.film', 'schedule.studio'])
            ->where('id', $id)
            ->where('user_id', $currentUserId)
            ->first(); 

        if (!$booking) {
            // Jika tiket tidak ditemukan ATAU tidak dimiliki oleh user yang login
            abort(403, 'Akses tidak diizinkan. Tiket ini tidak ditemukan atau bukan milik Anda.');
        }

        // MENGARAHKAN KE resources/views/riwayat_detail.blade.php
        return view('user.riwayat_detail', compact('booking'));
    }

    /**
     * Memproses pembatalan pemesanan.
     */
    public function cancelBooking(Booking $booking)
    {
        // 1. Cek kepemilikan
        if ($booking->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak diizinkan membatalkan pesanan ini.');
        }

        // 2. BISA DIBATALKAN HANYA JIKA STATUS MASIH PENDING
        if ($booking->booking_status !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah dikonfirmasi atau sudah dibatalkan sebelumnya.');
        }

        // 3. Lakukan Pembatalan
        $booking->update([
            'booking_status' => 'cancelled',
            'payment_status' => 'refund_pending' 
        ]);

        return redirect()->route('riwayat')
                         ->with('success', 'Pesanan berhasil dibatalkan. Dana akan diproses sesuai kebijakan.');
    }
}
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
        $userId = Auth::id();

        $bookings = Booking::with(['schedule.film', 'schedule.studio'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('user.riwayat', [
            'bookings' => $bookings
        ]);
    }

    /**
     * MENAMBAHKAN FUNGSI INI:
     * Menampilkan detail tiket spesifik (E-Ticket).
     */
    public function show($id)
    {
        // 1. Cari booking berdasarkan ID
        // 2. PENTING: Pastikan 'user_id' cocok dengan user yang login (Auth::id())
        //    agar user tidak bisa mengintip tiket orang lain.
        $booking = Booking::with(['schedule.film', 'schedule.studio'])
            ->where('id', $id)
            ->where('user_id', Auth::id()) 
            ->firstOrFail(); // Jika tidak ketemu atau bukan miliknya, tampilkan 404 Error

        // 3. Kirim data ke view 'user.detail'
        return view('user.detail', [
            'booking' => $booking
        ]);
    }
}
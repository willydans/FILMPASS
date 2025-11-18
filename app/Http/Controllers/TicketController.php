<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar semua film (untuk halaman /movies)
     */
    public function index()
    {
        $films = Film::orderBy('title', 'asc')->get();
        return view('movies', ['films' => $films]);
    }

    /**
     * Menampilkan halaman pesan tiket (Pilih Jadwal)
     * @param int $filmId
     */
    public function create($filmId)
    {
        // 1. Ambil film dari database
        $film = Film::findOrFail($filmId);
        
        // 2. Ambil semua jadwal untuk film ini yang belum lewat
        // Kita juga load relasi 'studio' agar bisa menampilkan nama bioskop
        $schedules = Schedule::with('studio')
            ->where('film_id', $filmId)
            ->where('start_time', '>', now()) // Hanya jadwal masa depan
            ->orderBy('start_time', 'asc')
            ->get();
        
        // Jika tidak ada jadwal, beri pesan
        if ($schedules->isEmpty()) {
            return redirect()->back()->with('error', 'Maaf, belum ada jadwal tayang untuk film ini.');
        }

        // Kirim data ke view
        return view('pesan_tiket', [
            'film' => $film,
            'schedules' => $schedules
        ]);
    }
    
    /**
     * Memproses pemesanan tiket
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_count' => 'required|integer|min:1|max:10',
        ]);

        // 2. Ambil detail jadwal untuk menghitung harga
        $schedule = Schedule::findOrFail($validated['schedule_id']);
        
        // 3. Hitung Total Harga
        $totalPrice = $schedule->price * $validated['seat_count'];

        // 4. Simpan Booking ke Database
        // Pastikan user sudah login (middleware 'auth' di rute menangani ini)
        $booking = Booking::create([
            'schedule_id' => $schedule->id,
            'user_id' => Auth::id(),
            'seat_count' => $validated['seat_count'],
            'total_price' => $totalPrice,
            'booking_status' => 'confirmed', // Langsung konfirmasi untuk demo
        ]);
        
        // 5. Redirect ke halaman Riwayat dengan pesan sukses
        return redirect()->route('riwayat.detail', $booking->id)
                         ->with('success', 'Pemesanan tiket berhasil!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar semua film (untuk halaman /movies)
     */
    public function index()
    {
        $films = Film::orderBy('title', 'asc')->get();
        
        // Mengarah ke resources/views/movies.blade.php
        return view('movies', ['films' => $films]);
    }

    /**
     * Menampilkan halaman detail film & pilih jadwal
     */
    public function create($filmId)
    {
        $film = Film::findOrFail($filmId);
        
        // Ambil jadwal yang akan datang
        $schedules = Schedule::with(['studio', 'bookings'])
            ->where('film_id', $filmId)
            ->where('start_time', '>=', now()) 
            ->where('status', 'terjadwal') 
            ->orderBy('start_time', 'asc')
            ->get();

        // Hitung Sisa Kursi untuk tampilan awal
        $schedules->map(function ($schedule) {
            $bookedSeats = $schedule->bookings
                ->where('booking_status', '!=', 'cancelled')
                ->sum('seat_count');
            
            $schedule->available_seats = $schedule->studio->capacity - $bookedSeats;
            return $schedule;
        });

        // Mengarah ke resources/views/ticket_create.blade.php
        return view('ticket_create', [
            'film' => $film,
            'schedules' => $schedules
        ]);
    }

    /**
     * STEP 1: TAMPILKAN DENAH KURSI (Visual Seat Selection)
     */
    public function selectSeats($scheduleId)
    {
        $schedule = Schedule::with('studio')->findOrFail($scheduleId);

        // 1. Ambil kursi yang SUDAH TERBOOKING
        $bookedSeatsData = Booking::where('schedule_id', $schedule->id)
            ->where('booking_status', '!=', 'cancelled')
            ->pluck('seats')
            ->toArray();

        $bookedSeats = [];
        foreach ($bookedSeatsData as $seatString) {
            $seats = array_map('trim', explode(',', $seatString));
            $bookedSeats = array_merge($bookedSeats, $seats);
        }

        // 2. Generate Layout Kursi
        $capacity = $schedule->studio->capacity;
        $seatsPerRow = 10; // Ubah sesuai layout studio
        $totalRows = ceil($capacity / $seatsPerRow);
        
        $rows = [];
        $rowLabels = range('A', 'Z');

        for ($i = 0; $i < $totalRows; $i++) {
            $rowLabel = $rowLabels[$i] ?? 'Z';
            $seatsInThisRow = [];
            
            for ($j = 1; $j <= $seatsPerRow; $j++) {
                $seatNumber = ($i * $seatsPerRow) + $j;
                if ($seatNumber > $capacity) break;

                $seatCode = $rowLabel . $j;
                $status = in_array($seatCode, $bookedSeats) ? 'booked' : 'available';
                
                $seatsInThisRow[] = [
                    'code' => $seatCode,
                    'status' => $status
                ];
            }
            $rows[] = ['label' => $rowLabel, 'seats' => $seatsInThisRow];
        }

        // Mengarah ke resources/views/select_seats.blade.php
        return view('select_seats', compact('schedule', 'rows'));
    }

    /**
     * STEP 2: HALAMAN CHECKOUT (Ringkasan & Pembayaran)
     */
    public function showCheckout(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'selected_seats' => 'required|string',
        ]);

        $schedule = Schedule::with(['film', 'studio'])->findOrFail($request->schedule_id);
        
        $seats = explode(',', $request->selected_seats);
        $totalSeats = count($seats);
        
        $pricePerTicket = $schedule->price;
        $adminFee = 2000;
        $totalPrice = ($pricePerTicket * $totalSeats) + $adminFee;

        // Mengarah ke resources/views/checkout.blade.php
        return view('checkout', compact('schedule', 'seats', 'totalPrice', 'adminFee'));
    }

    /**
     * STEP 3: PROSES PEMBAYARAN (Simpan ke Database)
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seats' => 'required|array',
            'payment_method' => 'required|string',
            'total_price' => 'required|numeric'
        ]);

        $booking = Booking::create([
            'schedule_id' => $request->schedule_id,
            'user_id' => Auth::id(),
            'seat_count' => count($request->seats),
            'seats' => implode(', ', $request->seats),
            'total_price' => $request->total_price,
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        // Redirect ke halaman detail riwayat
        return redirect()->route('riwayat.detail', $booking->id)
                         ->with('success', 'Pembayaran Berhasil! Tiket Anda telah terbit.');
    }
}
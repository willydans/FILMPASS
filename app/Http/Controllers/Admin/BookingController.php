<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman daftar pemesanan
     */
    public function index(Request $request)
    {
        // Filter
        $search = $request->get('search');
        $status = $request->get('status');
        $date = $request->get('date');

        // Query bookings
        $bookings = Booking::with(['user', 'schedule.film', 'schedule.studio'])
            ->when($search, function($query, $search) {
                return $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('schedule.film', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            })
            ->when($status, function($query, $status) {
                return $query->where('booking_status', $status);
            })
            ->when($date, function($query, $date) {
                return $query->whereHas('schedule', function($q) use ($date) {
                    $q->whereDate('start_time', $date);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistik
        $totalPemesanan = Booking::count();
        $dikonfirmasi = Booking::where('booking_status', 'confirmed')->count();
        $menunggu = Booking::where('booking_status', 'pending')->count();
        $totalPendapatan = Booking::where('booking_status', 'confirmed')->sum('total_price');

        return view('admin.bookings.index', compact(
            'bookings',
            'totalPemesanan',
            'dikonfirmasi',
            'menunggu',
            'totalPendapatan',
            'search',
            'status',
            'date'
        ));
    }

    /**
     * Menampilkan form pemesanan baru (untuk kasir)
     */
    public function create()
    {
        // Ambil jadwal hari ini dan besok yang masih tersedia
        $schedules = Schedule::with(['film', 'studio'])
            ->where('start_time', '>=', now())
            ->where('start_time', '<=', now()->addDays(7))
            ->orderBy('start_time', 'asc')
            ->get();

        // Hitung kursi tersedia
        foreach ($schedules as $schedule) {
            $bookedSeats = Booking::where('schedule_id', $schedule->id)
                ->where('booking_status', '!=', 'cancelled')
                ->sum('seat_count');
            
            $schedule->available_seats = $schedule->studio->capacity - $bookedSeats;
        }

        // Ambil daftar user (pelanggan)
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.bookings.create', compact('schedules', 'users'));
    }

    /**
     * Menyimpan pemesanan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'seat_count' => 'required|integer|min:1|max:10',
            'payment_method' => 'required|in:cash,transfer,qris,ewallet',
        ]);

        // Ambil jadwal
        $schedule = Schedule::with('studio')->findOrFail($request->schedule_id);

        // Cek ketersediaan kursi
        $bookedSeats = Booking::where('schedule_id', $schedule->id)
            ->where('booking_status', '!=', 'cancelled')
            ->sum('seat_count');
        
        $availableSeats = $schedule->studio->capacity - $bookedSeats;

        if ($request->seat_count > $availableSeats) {
            return back()->withErrors([
                'seat_count' => "Kursi tersedia hanya {$availableSeats} kursi."
            ])->withInput();
        }

        // Hitung total harga
        $totalPrice = $schedule->price * $request->seat_count;

        // Simpan booking (langsung confirmed untuk admin)
        $booking = Booking::create([
            'schedule_id' => $request->schedule_id,
            'user_id' => $request->user_id,
            'seat_count' => $request->seat_count,
            'total_price' => $totalPrice,
            'booking_status' => 'confirmed', // Langsung confirmed
        ]);

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Pemesanan berhasil dibuat!');
    }

    /**
     * Menampilkan detail pemesanan
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'schedule.film', 'schedule.studio']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update status pemesanan
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update([
            'booking_status' => $request->booking_status,
        ]);

        return back()->with('success', 'Status pemesanan berhasil diperbarui!');
    }

    /**
     * Hapus pemesanan
     */
    public function destroy(Booking $booking)
    {
        // Hanya bisa hapus jika statusnya cancelled atau pending
        if ($booking->booking_status === 'confirmed') {
            return back()->withErrors([
                'error' => 'Tidak dapat menghapus pemesanan yang sudah dikonfirmasi. Batalkan terlebih dahulu.'
            ]);
        }

        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Pemesanan berhasil dihapus!');
    }
}
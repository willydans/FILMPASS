<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config; 
use Midtrans\Snap;   
use Midtrans\Transaction; // Import Transaction untuk cek status

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

        return view('checkout', compact('schedule', 'seats', 'totalPrice', 'adminFee'));
    }

    /**
     * STEP 3: PROSES PEMBAYARAN & MIDTRANS
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seats' => 'required|array',
            'payment_method' => 'required|string',
            'total_price' => 'required|numeric'
        ]);

        // 1. Simpan Booking ke Database (Status Pending & Unpaid)
        $booking = Booking::create([
            'schedule_id' => $request->schedule_id,
            'user_id' => Auth::id(),
            'seat_count' => count($request->seats),
            'seats' => implode(', ', $request->seats),
            'total_price' => $request->total_price,
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'booking_status' => 'pending',
        ]);

        // 2. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 3. Siapkan Parameter Midtrans Snap
        // Gunakan Format BOOK-{ID} agar konsisten saat cek status
        $orderId = 'BOOK-' . $booking->id;

        $midtransParams = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id' => 'TICKET-' . $booking->id,
                    'price' => (int) $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Tiket Film: ' . $booking->schedule->film->title,
                ]
            ]
        ];

        // Opsional: Filter metode pembayaran
        if ($request->payment_method == 'qris') {
            $midtransParams['enabled_payments'] = ['qris', 'gopay', 'shopeepay'];
        } elseif ($request->payment_method == 'bank_transfer') {
            $midtransParams['enabled_payments'] = ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'];
        }

        try {
            // 4. Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($midtransParams);
            
            // 5. Arahkan ke Halaman Pembayaran
            return view('payment_page', [
                'snapToken' => $snapToken, 
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * STEP 4: CALLBACK SUKSES DARI FRONTEND
     */
    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Asumsi: Jika frontend trigger success, kemungkinan besar sudah paid.
        // Namun Webhook adalah kebenaran mutlak.
        $booking->update([
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        return redirect()->route('riwayat.detail', $booking->id)
                         ->with('success', 'Pembayaran Berhasil! Tiket Anda sudah aktif.');
    }

    /**
     * FUNGSI BARU: Cek Status Transaksi Manual ke Midtrans (REAL CHECK)
     */
    public function checkStatus($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Hanya proses jika tiket ini milik user yg login
        if ($booking->user_id !== Auth::id()) {
             abort(403, 'Akses ditolak.');
        }
        
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Order ID sesuai format di processPayment
        $orderId = 'BOOK-' . $booking->id;

        try {
            // Panggil API Midtrans untuk cek status
            $status = Transaction::status($orderId);
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status ?? null;
            
            $message = "Status pembayaran belum berubah.";
            $alertType = "info";

            // Logika Update Status Database berdasarkan Respon Midtrans Real-time
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $booking->update(['payment_status' => 'challenge', 'booking_status' => 'pending']);
                    $message = "Pembayaran sedang diverifikasi.";
                } else {
                    $booking->update(['payment_status' => 'paid', 'booking_status' => 'confirmed']);
                    $message = "Pembayaran berhasil dikonfirmasi!";
                    $alertType = "success";
                }
            } else if ($transactionStatus == 'settlement') {
                $booking->update(['payment_status' => 'paid', 'booking_status' => 'confirmed']);
                $message = "Pembayaran berhasil dikonfirmasi!";
                $alertType = "success";
            } else if ($transactionStatus == 'pending') {
                $booking->update(['payment_status' => 'unpaid', 'booking_status' => 'pending']);
                $message = "Menunggu pembayaran. Silakan selesaikan transaksi Anda.";
                $alertType = "warning";
            } else if ($transactionStatus == 'deny') {
                $booking->update(['payment_status' => 'failed', 'booking_status' => 'cancelled']);
                $message = "Pembayaran ditolak.";
                $alertType = "error";
            } else if ($transactionStatus == 'expire') {
                $booking->update(['payment_status' => 'expired', 'booking_status' => 'cancelled']);
                $message = "Waktu pembayaran habis. Pesanan dibatalkan.";
                $alertType = "error";
            } else if ($transactionStatus == 'cancel') {
                $booking->update(['payment_status' => 'cancelled', 'booking_status' => 'cancelled']);
                $message = "Pembayaran dibatalkan.";
                $alertType = "error";
            }

            return back()->with($alertType, $message);

        } catch (\Exception $e) {
            // Jika transaksi tidak ditemukan di Midtrans (misal belum klik bayar sama sekali)
            return back()->with('error', 'Transaksi belum ditemukan atau terjadi kesalahan koneksi.');
        }
    }
}
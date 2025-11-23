<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * Handle Midtrans Notification (Webhook)
     */
    public function notification(Request $request)
    {
        // 1. Konfigurasi Midtrans (Gunakan config() agar aman)
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Log request untuk debugging
        // Log::info('Midtrans Webhook:', $request->all());

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Notification Invalid'], 400);
        }

        // 2. Ambil parameter penting
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // 3. Ekstrak ID Booking
        // Format Order ID: "BOOK-{id}-{timestamp}"
        $parts = explode('-', $orderId);
        $bookingId = $parts[1] ?? null;

        if (!$bookingId) {
            return response()->json(['message' => 'Invalid Order ID'], 400);
        }

        // 4. Cari Data Booking (Model ini otomatis mengarah ke tabel 'bookings')
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // 5. Logika Update Status (Menggunakan variabel $booking yang benar)
        
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $booking->update([
                        'payment_status' => 'challenge',
                        'booking_status' => 'pending'
                    ]);
                } else {
                    $booking->update([
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed'
                    ]);
                }
            }
        } 
        else if ($transaction == 'settlement') {
            // Pembayaran Berhasil (Transfer/E-Wallet)
            $booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed'
            ]);
        } 
        else if ($transaction == 'pending') {
            // Menunggu Pembayaran
            $booking->update([
                'payment_status' => 'unpaid',
                'booking_status' => 'pending'
            ]);
        } 
        else if ($transaction == 'deny') {
            // Pembayaran Ditolak
            $booking->update([ // Perbaikan: Pakai $booking (singular)
                'payment_status' => 'failed',
                'booking_status' => 'cancelled'
            ]);
        } 
        else if ($transaction == 'expire') {
            // Waktu Habis
            $booking->update([ // Perbaikan: Pakai $booking (singular)
                'payment_status' => 'expired',
                'booking_status' => 'cancelled'
            ]);
        } 
        else if ($transaction == 'cancel') {
            // Dibatalkan
            $booking->update([ // Perbaikan: Pakai $booking (singular)
                'payment_status' => 'cancelled',
                'booking_status' => 'cancelled'
            ]);
        }

        Log::info("Booking ID {$bookingId} updated to: {$booking->payment_status}");

        return response()->json(['message' => 'Notification processed successfully']);
    }
}
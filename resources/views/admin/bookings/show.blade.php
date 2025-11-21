@extends('layouts.admin')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Pemesanan
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Pemesanan</h1>
        <p class="text-gray-600">E-Ticket Digital</p>
    </div>

    <!-- Notifikasi Success -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg flex items-start">
            <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-semibold text-green-800">Berhasil!</p>
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- E-Ticket Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-gray-200">
        
        <!-- Header Ticket -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold mb-1">FilmPass E-Ticket</h2>
                    <p class="text-indigo-100 text-sm">Kode Booking: <span class="font-mono font-bold">BK{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span></p>
                </div>
                <!-- Status Badge -->
                @if($booking->booking_status == 'confirmed')
                    <div class="bg-green-500 text-white px-4 py-2 rounded-full font-semibold text-sm">
                        ✓ Dikonfirmasi
                    </div>
                @elseif($booking->booking_status == 'pending')
                    <div class="bg-yellow-500 text-white px-4 py-2 rounded-full font-semibold text-sm">
                        ⏱ Menunggu
                    </div>
                @else
                    <div class="bg-red-500 text-white px-4 py-2 rounded-full font-semibold text-sm">
                        ✕ Dibatalkan
                    </div>
                @endif
            </div>
        </div>

        <!-- Film Info Section -->
        <div class="p-8 border-b border-gray-200">
            <div class="flex gap-6">
                <!-- Poster -->
                @if($booking->schedule->film->poster_path)
                    <img src="{{ asset('storage/' . $booking->schedule->film->poster_path) }}" 
                         alt="{{ $booking->schedule->film->title }}" 
                         class="w-32 h-48 object-cover rounded-lg shadow-md">
                @else
                    <div class="w-32 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                @endif
                
                <!-- Film Details -->
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->schedule->film->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ $booking->schedule->film->description }}</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm">{{ $booking->schedule->film->duration_minutes }} menit</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-sm font-semibold">{{ $booking->schedule->film->rating }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="p-8 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Column 1 -->
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Pelanggan</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $booking->user->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Studio</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->schedule->studio->name }}</p>
                        <p class="text-sm text-gray-600">{{ $booking->schedule->studio->type }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Jumlah Tiket</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->seat_count }} Kursi</p>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal Tayang</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->schedule->start_time->translatedFormat('l, d F Y') }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Waktu Tayang</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->schedule->start_time->format('H:i') }} WIB</p>
                        <p class="text-sm text-gray-600">s/d {{ $booking->schedule->end_time->format('H:i') }} WIB</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Section (Optional) -->
        <div class="p-8 border-t border-gray-200 text-center bg-white">
            <p class="text-sm text-gray-600 mb-4">Tunjukkan kode ini saat masuk bioskop</p>
            <div class="inline-block bg-white p-4 rounded-lg border-2 border-dashed border-gray-300">
                <div class="w-48 h-48 bg-gray-100 flex items-center justify-center rounded">
                    <!-- Placeholder untuk QR Code -->
                    <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Kode Booking: <span class="font-mono font-bold">BK{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span></p>
        </div>

        <!-- Footer dengan Important Notes -->
        <div class="p-6 bg-gray-100 border-t border-gray-200">
            <h4 class="font-semibold text-gray-800 mb-2 text-sm">Catatan Penting:</h4>
            <ul class="text-xs text-gray-600 space-y-1">
                <li>• Harap datang 15 menit sebelum waktu tayang</li>
                <li>• E-Ticket ini berlaku untuk 1 kali masuk</li>
                <li>• Tidak dapat ditukar atau dikembalikan</li>
                <li>• Tunjukkan E-Ticket ini kepada petugas</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="p-6 bg-white border-t border-gray-200 flex justify-between items-center">
            
            <!-- Update Status (Untuk Admin) -->
            @if($booking->booking_status == 'pending')
                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="booking_status" value="confirmed">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-semibold transition duration-200">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            @endif

            @if($booking->booking_status == 'confirmed')
                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan pemesanan ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="booking_status" value="cancelled">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg font-semibold transition duration-200">
                        Batalkan Pemesanan
                    </button>
                </form>
            @endif

            <!-- Print Button -->
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-semibold inline-flex items-center transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print E-Ticket
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .max-w-4xl, .max-w-4xl * {
            visibility: visible;
        }
        .max-w-4xl {
            position: absolute;
            left: 0;
            top: 0;
        }
        button, a {
            display: none !important;
        }
    }
</style>

@endsection
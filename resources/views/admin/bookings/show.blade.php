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
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Pemesanan</h1>
                <p class="text-gray-600">ID Transaksi: #{{ $booking->id }}</p>
            </div>
            <div class="hidden md:block">
                <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak / PDF
                </button>
            </div>
        </div>
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

    <!-- E-Ticket Card (Tampilan Admin) -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-gray-200 print:border-0 print:shadow-none">
        
        <!-- Header Ticket -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white print:bg-none print:text-black print:border-b print:border-black">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold mb-1">FilmPass Admin Ticket</h2>
                    <p class="text-indigo-100 text-sm print:text-black">Kode Booking: <span class="font-mono font-bold">BK{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span></p>
                </div>
                <!-- Status Badge -->
                <div class="print:hidden">
                    @if($booking->booking_status == 'confirmed')
                        <div class="bg-green-500 text-white px-4 py-2 rounded-full font-semibold text-sm shadow-sm">
                            ✓ Dikonfirmasi
                        </div>
                    @elseif($booking->booking_status == 'pending')
                        <div class="bg-yellow-500 text-white px-4 py-2 rounded-full font-semibold text-sm shadow-sm">
                            ⏱ Menunggu
                        </div>
                    @else
                        <div class="bg-red-500 text-white px-4 py-2 rounded-full font-semibold text-sm shadow-sm">
                            ✕ Dibatalkan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Film Info Section -->
        <div class="p-8 border-b border-gray-200">
            <div class="flex gap-6">
                <!-- Poster -->
                @if($booking->schedule->film->poster_path)
                    <img src="{{ asset('storage/' . $booking->schedule->film->poster_path) }}" 
                         alt="{{ $booking->schedule->film->title }}" 
                         class="w-32 h-48 object-cover rounded-lg shadow-md print:hidden">
                @else
                    <div class="w-32 h-48 bg-gray-200 rounded-lg flex items-center justify-center print:hidden">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                @endif
                
                <!-- Film Details -->
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $booking->schedule->film->title }}</h3>
                    <div class="flex flex-wrap gap-2 mb-4 text-sm">
                        <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 border border-gray-200">{{ explode(',', $booking->schedule->film->genre)[0] }}</span>
                        <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 border border-gray-200">{{ $booking->schedule->film->duration_minutes }} Menit</span>
                        <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 border border-gray-200">{{ $booking->schedule->film->rating }}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mt-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Jadwal Tayang</p>
                            <p class="font-semibold text-gray-900 text-lg">
                                {{ $booking->schedule->start_time->translatedFormat('l, d F Y') }}
                            </p>
                            <p class="text-indigo-600 font-bold">
                                {{ $booking->schedule->start_time->format('H:i') }} WIB
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Lokasi</p>
                            <p class="font-semibold text-gray-900 text-lg">
                                {{ $booking->schedule->studio->name }}
                            </p>
                            <p class="text-gray-600 text-sm">
                                {{ $booking->schedule->studio->type }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pembayaran & Kursi -->
        <div class="p-8 bg-gray-50 print:bg-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Informasi User -->
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">Informasi Pelanggan</h4>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500">Nama</p>
                            <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="font-medium text-gray-900">{{ $booking->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Waktu Pemesanan</p>
                            <p class="font-medium text-gray-900">{{ $booking->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Kursi -->
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">Detail Kursi</h4>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-500">Jumlah Tiket</p>
                            <p class="font-medium text-gray-900">{{ $booking->seat_count }} Tiket</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Nomor Kursi</p>
                            <p class="font-bold text-orange-600 text-xl break-words">
                                {{ $booking->seats }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div>
                    <h4 class="font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">Pembayaran</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Metode</span>
                            <span class="text-sm font-medium uppercase">{{ $booking->payment_method ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="text-sm font-bold {{ $booking->payment_status == 'paid' ? 'text-green-600' : 'text-red-600' }} uppercase">
                                {{ $booking->payment_status }}
                            </span>
                        </div>
                        <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-xl text-indigo-700">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Admin Actions Footer -->
        <div class="p-6 bg-gray-100 border-t border-gray-200 flex justify-end gap-3 print:hidden">
            @if($booking->booking_status == 'pending')
                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="booking_status" value="confirmed">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold shadow-sm transition">
                        Verifikasi Pembayaran
                    </button>
                </form>
            @endif

            @if($booking->booking_status != 'cancelled')
                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pemesanan ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="booking_status" value="cancelled">
                    <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 border border-red-200 px-6 py-2 rounded-lg font-semibold transition">
                        Batalkan Pesanan
                    </button>
                </form>
            @endif
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
            width: 100%;
        }
        .print\:hidden {
            display: none !important;
        }
        .bg-gray-50 {
            background-color: white !important;
        }
        .shadow-xl {
            box-shadow: none !important;
        }
        .border-2 {
            border: 1px solid #ccc !important;
        }
    }
</style>
@endsection
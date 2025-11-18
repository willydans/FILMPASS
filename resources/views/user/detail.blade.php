<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>Detail Tiket #{{ $booking->id }} - FilmPass</title>
</head>
<body class="bg-slate-900">
    
    @include('partials.header')

    <main class="bg-gray-50 text-gray-900 pt-32 pb-16 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 text-center">
            
            <header class="text-center mb-10">
                <h1 class="text-3xl font-bold text-orange-400">Tiket Anda Siap!</h1>
                <p class="text-gray-600 mb-10">Tunjukkan kode QR ini di loket masuk bioskop.</p>
            </header>
            
            <div class="max-w-md mx-auto bg-slate-800 rounded-2xl shadow-2xl overflow-hidden text-left">
                
                <div class="p-6 pb-3 border-b border-dashed border-slate-700">
                    <p class="text-xs font-semibold text-orange-400 uppercase tracking-wider mb-2">
                        E-Ticket FilmPass #{{ $booking->id }}
                    </p>
                    <h2 class="text-2xl md:text-2xl font-black mb-1 text-white">
                        {{ $booking->schedule->film->title }}
                    </h2>
                    <p class="text-gray-400 text-sm">
                        {{-- Data Dummy untuk genre/durasi karena belum ada di DB, bisa disesuaikan --}}
                        {{ $booking->schedule->film->rating ?? 'SU' }} | {{ $booking->schedule->film->duration_minutes ?? 120 }} Menit
                    </p>
                </div>

                <div class="p-6 pt-3 grid grid-cols-2 gap-y-4 text-gray-300">
                    
                    {{-- Detail Kiri --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Bioskop & Studio</p>
                            <p class="font-semibold text-sm text-white">
                                {{ $booking->schedule->studio->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Tayang</p>
                            <p class="font-semibold text-sm text-white">
                                {{ $booking->schedule->start_time->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- Detail Kanan --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Waktu Tayang</p>
                            <p class="font-bold text-md text-orange-400">
                                {{ $booking->schedule->start_time->format('H:i') }} WIB
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Kursi</p>
                            <p class="font-bold text-md text-white">
                                {{ $booking->seat_count }} Kursi
                            </p>
                        </div>
                    </div>
                    
                </div>
                
                <div class="relative h-4 bg-slate-800">
                    <div class="absolute inset-x-0 top-1/2 transform -translate-y-1/2 flex justify-between px-[-10px]">
                        <div class="w-6 h-6 rounded-full bg-gray-50 transform -ml-3"></div>
                        <div class="w-6 h-6 rounded-full bg-gray-50 transform -mr-3"></div>
                    </div>
                    <div class="absolute inset-x-4 top-1/2 border-t-2 border-dashed border-slate-600"></div>
                </div>

                <div class="p-6 bg-slate-900 text-center">
                    
                    <div class="w-36 h-36 mx-auto p-2 bg-white rounded-lg mb-4 flex items-center justify-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->id }}-{{ $booking->created_at->timestamp }}" 
                             alt="Kode QR Tiket" 
                             class="w-full h-full object-contain">
                    </div>
                    
                    <p class="text-xl font-mono tracking-widest mb-2 text-white">
                        FP-{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        Tunjukkan kode di atas untuk scan tiket.
                    </p>
                    
                    <div class="border-t border-slate-800 pt-4 mt-4 text-sm text-gray-300">
                        <div class="flex justify-between mb-2">
                            <span>Total Pembayaran</span>
                            <span class="font-bold text-orange-400">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status</span>
                            <span class="uppercase font-bold {{ $booking->booking_status == 'confirmed' ? 'text-green-500' : 'text-red-500' }}">
                                {{ $booking->booking_status }}
                            </span>
                        </div>
                    </div>

                </div>
            </div> {{-- End E-TICKET CARD --}}

            <div class="max-w-xl mx-auto mt-8 flex justify-center space-x-4">
                 <a href="{{ route('riwayat') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors px-4 py-2 rounded-lg font-medium border border-gray-300 hover:border-gray-400">
                    <span class="mr-2">&larr;</span> Kembali ke Riwayat
                </a>
                <button onclick="window.print()" class="inline-flex items-center bg-slate-800 hover:bg-slate-700 text-white transition-colors px-4 py-2 rounded-lg font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Tiket
                </button>
            </div>

        </div> 
    </main>

    @include('partials.footer')

</body>
</html>
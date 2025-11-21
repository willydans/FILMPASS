<!DOCTYPE html>
<html lang="id">
<head>
    {{-- 1. MEMANGGIL ELEMEN HEAD (TERMASUK TAILWIND & ALPINE.JS CDN) --}}
    @include('partials.head')
    <title>Riwayat Pemesanan - FilmPass</title>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col font-inter">
    
    {{-- 2. MEMANGGIL ELEMEN HEADER (NAVIGASI) --}}
    @include('partials.header')

    {{-- KONTEN UTAMA RIWAYAT --}}
    <main class="flex-grow">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            
            <header class="text-center mb-12">
                <h1 class="text-3xl font-bold text-orange-400">Riwayat Pemesanan Anda</h1>
                <p class="text-gray-600 mb-10">Semua tiket dan film favorit Anda tersimpan di sini.</p>
            </header>
            
            <div class="max-w-4xl mx-auto">
                
                <div class="space-y-8">
                    
                    {{-- LOOPING DATA DARI DATABASE --}}
                    @forelse ($bookings as $booking)
                        {{-- 
                          Logic Warna Status
                        --}}
                        @php
                            $statusColor = 'text-orange-400';
                            $statusText = 'Tiket Berhasil';
                            $borderColor = 'hover:border-orange-500';

                            if ($booking->schedule->start_time < now()) {
                                $statusColor = 'text-gray-400';
                                $statusText = 'Selesai (Sudah Tayang)';
                                $borderColor = 'hover:border-gray-500';
                            } elseif ($booking->booking_status == 'cancelled') {
                                $statusColor = 'text-red-500';
                                $statusText = 'Dibatalkan';
                                $borderColor = 'hover:border-red-500';
                            }
                        @endphp

                        <div class="bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-700 {{ $borderColor }} transition duration-300">
                            
                            {{-- Header Kartu --}}
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-700/50 pb-4 mb-4">
                                <div>
                                    <span class="text-sm font-semibold text-gray-400">ID Transaksi: #{{ $booking->id }}</span>
                                    <h2 class="text-white text-2xl font-bold mt-1">
                                        {{ $booking->schedule->film->title }}
                                    </h2>
                                </div>
                                <span class="text-sm font-semibold {{ $statusColor }} mt-2 md:mt-0 px-3 py-1 bg-slate-700 rounded-full">
                                    {{ $statusText }}
                                </span>
                            </div>

                            {{-- Detail Tiket --}}
                            {{-- Ubah grid menjadi 5 kolom di desktop agar muat untuk kursi --}}
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-gray-300">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tanggal Tayang</p>
                                    <p class="font-semibold">
                                        {{ $booking->schedule->start_time->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Waktu</p>
                                    <p class="font-semibold">
                                        {{ $booking->schedule->start_time->format('H:i') }} WIB
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Bioskop</p>
                                    <p class="font-semibold">
                                        {{ $booking->schedule->studio->name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Tiket</p>
                                    <p class="font-semibold">
                                        {{ $booking->seat_count }} Tiket
                                    </p>
                                </div>
                                {{-- TAMBAHAN: Menampilkan Kursi --}}
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nomor Kursi</p>
                                    <p class="font-bold text-orange-400 break-words">
                                        {{ $booking->seats }}
                                    </p>
                                </div>
                            </div>

                            {{-- Footer Kartu (Total Harga & Link Detail) --}}
                            <div class="flex justify-between items-center mt-6 pt-4 border-t border-slate-700/30">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Bayar</p>
                                    <p class="font-bold text-lg text-white">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <a href="{{ route('riwayat.detail', $booking->id) }}" 
                                   class="text-orange-400 hover:text-orange-300 transition-colors font-medium text-sm border-b border-orange-400 pb-0.5 hover:border-orange-300">
                                    Lihat E-Ticket &rarr;
                                </a>
                            </div>
                        </div>
                    
                    @empty
                        {{-- TAMPILAN JIKA BELUM ADA RIWAYAT --}}
                        <div class="text-center py-12 bg-slate-800 rounded-xl border border-slate-700">
                            <div class="inline-block p-4 rounded-full bg-slate-700 mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Riwayat</h3>
                            <p class="text-gray-400 mb-6">Anda belum pernah memesan tiket film.</p>
                            <a href="{{ url('/') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-block">
                                Pesan Tiket Sekarang
                            </a>
                        </div>
                    @endforelse

                </div>
                
                {{-- Pagination Links --}}
                <div class="mt-12 flex justify-center">
                    {{ $bookings->links() }}
                </div>

            </div>

        </div> 
    </main>

    {{-- 3. MEMANGGIL ELEMEN FOOTER --}}
    @include('partials.footer')

</body>
</html>
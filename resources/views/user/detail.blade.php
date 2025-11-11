<!DOCTYPE html>
<html lang="id">
<head>
    {{-- 1. MEMANGGIL ELEMEN HEAD (TERMASUK TAILWIND & ALPINE.JS CDN) --}}
    @include('partials.head')
    <title>Detail Tiket - FilmPass</title>
</head>
<body class="bg-slate-900">
    
    {{-- 2. MEMANGGIL ELEMEN HEADER (NAVIGASI) --}}
    @include('partials.header')

    {{-- KONTEN UTAMA RIWAYAT --}}
    <main class="bg-gray-50 text-gray-900 pt-32 pb-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            
            <header class="text-center mb-10">
                <h1 class="text-3xl font-bold text-orange-400">Tiket Anda Siap!</h1>
                <p class="text-gray-600 mb-10">Tunjukkan kode QR ini di loket masuk bioskop.</p>
            </header>
            
            <!-- E-TICKET CARD -->
            <div class="max-w-md mx-auto bg-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                
                <!-- Bagian Informasi Film (Atas) - Padding dikurangi -->
                <div class="p-6 pb-3 border-b border-dashed border-slate-700">
                    <p class="text-xs font-semibold text-orange-400 uppercase tracking-wider mb-2">
                        E-Ticket FilmPass
                    </p>
                    <h2 class="text-2xl md:text-2xl font-black mb-1">Midnight Melody</h2>
                    <p class="text-gray-400 text-sm">Drama Musikal | 110 Menit | PG-13</p>
                </div>

                <!-- Detail Bioskop & Waktu - Padding & Gap dikurangi -->
                <div class="p-6 pt-3 grid grid-cols-2 gap-y-4 text-gray-300">
                    
                    {{-- Detail Kiri --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Bioskop & Studio</p>
                            <p class="font-semibold text-sm">CinemaX Premiere - Studio 3</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Tayang</p>
                            <p class="font-semibold text-sm">Jumat, 25 Oktober 2025</p>
                        </div>
                    </div>
                    
                    {{-- Detail Kanan --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Waktu Tayang</p>
                            <p class="font-bold text-md text-orange-400">19:30 WIB</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kursi Dipesan</p>
                            <p class="font-bold text-md">A5, A6</p>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Separator dengan Lubang Tiket -->
                <div class="relative h-4 bg-slate-900">
                    <div class="absolute inset-x-0 top-12 transform -translate-y-1/2 flex justify-between px-[-10px]">
                        <div class="w-5 h-5 rounded-full bg-slate-900 border border-slate-700 transform -ml-3"></div>
                        <div class="w-5 h-5 rounded-full bg-slate-900 border border-slate-700 transform -mr-3"></div>
                    </div>
                </div>

                <!-- Bagian QR Code & Transaksi (Bawah) - Padding & Margin dikurangi -->
                <div class="p-6 bg-slate-900 text-center">
                    
                     <!-- Placeholder QR Code - Margin dikurangi -->
                    <div class="w-36 h-36 mx-auto p-3 bg-white rounded-lg mb-4 flex items-center justify-center">
                        {{-- Ganti dengan QR code generator (contohnya menggunakan placehold) --}}
                        <img src="https://placehold.co/150x150/000/FFF?text=QR+CODE" alt="Kode QR Tiket" class="w-full h-full object-contain">
                    </div>
                    
                    <p class="text-xl font-mono tracking-widest mb-2">
                        FP-1234 5678 9012
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        Tunjukkan kode di atas untuk scan tiket. Masa berlaku hingga 25/10/2025 19:30
                    </p>
                    
                    <!-- Detail Harga & Transaksi - Margin & Padding dikurangi -->
                    <div class="border-t border-slate-800 pt-4 mt-4 text-sm text-gray-300">
                        <div class="flex justify-between mb-2">
                            <span>Total Pembayaran</span>
                            <span class="font-bold text-orange-400">Rp 100.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Metode Pembayaran</span>
                            <span>Kartu Kredit Visa</span>
                        </div>
                    </div>

                </div>
            </div> {{-- End E-TICKET CARD --}}

            <!-- Tombol Aksi -->
            <div class="max-w-xl mx-auto mt-8 flex justify-center space-x-4">
                 <a href="{{ route('riwayat') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors px-4 py-2 rounded-lg font-medium border border-gray-600 hover:border-white">
                    <span class="mr-2">&larr;</span> Kembali ke Riwayat
                </a>
            </div>

        </div> 
    </main>

    {{-- 3. MEMANGGIL ELEMEN FOOTER --}}
    @include('partials.footer')

</body>
</html>
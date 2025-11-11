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
            
            <!-- Hero Section Riwayat -->
            <header class="text-center mb-12">
                <h1 class="text-3xl font-bold text-orange-400">Riwayat Pemesanan Anda</h1>
                <p class="text-gray-600 mb-10">Semua tiket dan film favorit Anda tersimpan di sini.</p>
            </header>
            
            <!-- Konten Riwayat (Contoh Data Statis) -->
            <div class="max-w-4xl mx-auto">
                
                {{-- Konten ini diasumsikan hanya dapat diakses oleh pengguna yang sudah login --}}
                
                <div class="space-y-8">
                    
                    {{-- Contoh Transaksi 1 --}}
                    <div class="bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-700 hover:border-orange-500 transition duration-300">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-700/50 pb-4 mb-4">
                            <div>
                                <span class="text-sm font-semibold text-gray-400">ID Transaksi: FP-12345678</span>
                                <h2 class=" text-white text-2xl font-bold mt-1">Midnight Melody</h2>
                            </div>
                            <span class="text-sm font-semibold text-orange-400 mt-2 md:mt-0 px-3 py-1 bg-slate-700 rounded-full">
                                Tiket Berhasil
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-300">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Tayang</p>
                                <p class="font-semibold">25 Okt 2025</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Waktu</p>
                                <p class="font-semibold">19:30 WIB</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Bioskop</p>
                                <p class="font-semibold">CinemaX Premiere</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Tiket</p>
                                <p class="font-semibold">2 Kursi (A5, A6)</p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 space-x-3">
                            <a href="{{ route('detail') }}" class="text-orange-400 hover:text-orange-300 transition-colors font-medium text-sm border-b border-orange-400">
                                Lihat Detail Tiket
                            </a>
                        </div>
                    </div>

                    {{-- Contoh Transaksi 2 (Sudah Berlalu) --}}
                    <div class="bg-slate-800/50 p-6 rounded-xl shadow-lg border border-slate-700">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-slate-700/50 pb-4 mb-4">
                            <div>
                                <span class="text-sm font-semibold text-gray-500">ID Transaksi: FP-09876543</span>
                                <h2 class="text-2xl font-bold mt-1 text-gray-400">Jejak Sang Petualang</h2>
                            </div>
                            <span class="text-sm font-semibold text-red-400 mt-2 md:mt-0 px-3 py-1 bg-slate-700 rounded-full">
                                Selesai (Sudah Tayang)
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-400">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tanggal Tayang</p>
                                <p class="font-semibold">10 Jul 2025</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Waktu</p>
                                <p class="font-semibold">14:00 WIB</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Bioskop</p>
                                <p class="font-semibold">Grand XXI</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Tiket</p>
                                <p class="font-semibold">1 Kursi (B9)</p>
                            </div>
                        </div>

                    </div>
                    
                </div>
                
                {{-- Pagination (Opsional jika data riwayat banyak) --}}
                <div class="flex justify-center mt-12">
                    <nav class="flex space-x-2" aria-label="Pagination">
                        <button class="px-3 py-2 text-sm font-medium rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition-colors">
                            &laquo; Sebelumnya
                        </button>
                        <button class="px-4 py-2 text-sm font-medium rounded-lg bg-orange-500 text-white">
                            1
                        </button>
                        <button class="px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition-colors">
                            2
                        </button>
                        <button class="px-4 py-2 text-sm font-medium rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition-colors">
                            3
                        </button>
                        <button class="px-3 py-2 text-sm font-medium rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition-colors">
                            Selanjutnya &raquo;
                        </button>
                    </nav>
                </div>

            </div>

        </div> 
    </main>

    {{-- 3. MEMANGGIL ELEMEN FOOTER --}}
    @include('partials.footer')

</body>
</html>
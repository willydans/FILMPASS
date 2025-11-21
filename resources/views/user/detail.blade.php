<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>Detail Tiket #{{ $booking->id }} - FilmPass</title>
    <style>
        /* CSS khusus untuk garis potong tiket */
        .ticket-rip {
            position: relative;
            height: 20px;
            background-color: #1e293b; /* slate-800 */
            margin-left: -10px;
            margin-right: -10px;
        }
        .ticket-rip::before, .ticket-rip::after {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background-color: #0f172a; /* slate-900 (background body) */
            border-radius: 50%;
        }
        .ticket-rip::before { left: -10px; }
        .ticket-rip::after { right: -10px; }
        
        @media print {
            header, footer, .no-print { display: none; }
            body { background-color: white; color: black; }
            .print-area { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col font-inter">
    
    @include('partials.header')

    <main class="flex-grow pt-32 pb-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            
            <header class="text-center mb-10 no-print">
                <h1 class="text-3xl font-bold text-orange-400">Tiket Anda Siap!</h1>
                <p class="text-gray-400">Tunjukkan kode QR ini di loket masuk bioskop.</p>
            </header>
            
            {{-- KARTU TIKET --}}
            <div class="print-area max-w-md mx-auto bg-slate-800 rounded-2xl shadow-2xl overflow-hidden text-left border border-slate-700">
                
                {{-- BAGIAN ATAS (JUDUL) --}}
                <div class="p-6 pb-4 border-b border-dashed border-slate-600 relative">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-orange-400 uppercase tracking-wider mb-1">
                                E-Ticket FilmPass
                            </p>
                            <h2 class="text-2xl font-black text-white leading-tight mb-2">
                                {{ $booking->schedule->film->title }}
                            </h2>
                            <div class="flex items-center text-xs text-gray-400 space-x-2">
                                <span class="px-2 py-0.5 bg-slate-700 rounded text-white">
                                    {{ $booking->schedule->film->rating }}
                                </span>
                                <span>•</span>
                                <span>{{ $booking->schedule->film->duration_minutes }} Menit</span>
                                <span>•</span>
                                <span>{{ explode(',', $booking->schedule->film->genre)[0] }}</span>
                            </div>
                        </div>
                        {{-- ID Transaksi di pojok kanan --}}
                        <div class="text-right">
                            <span class="block text-xs text-gray-500">ID Booking</span>
                            <span class="block font-mono text-sm font-bold text-white">#{{ $booking->id }}</span>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN TENGAH (DETAIL) --}}
                <div class="p-6 grid grid-cols-2 gap-y-6 text-gray-300">
                    
                    {{-- Kolom Kiri --}}
                    <div class="space-y-5">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Bioskop</p>
                            <p class="font-bold text-white text-sm">
                                {{ $booking->schedule->studio->name }}
                            </p>
                            <p class="text-xs text-indigo-400 font-medium">
                                {{ $booking->schedule->studio->type }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tanggal</p>
                            <p class="font-bold text-white text-sm">
                                {{ $booking->schedule->start_time->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- Kolom Kanan --}}
                    <div class="space-y-5 text-right">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Jam Tayang</p>
                            <p class="font-bold text-orange-400 text-lg">
                                {{ $booking->schedule->start_time->format('H:i') }} WIB
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Kursi ({{ $booking->seat_count }})</p>
                            <p class="font-bold text-white text-lg break-words">
                                {{ $booking->seats }}
                            </p>
                        </div>
                    </div>
                    
                </div>
                
                {{-- GARIS POTONG --}}
                <div class="ticket-rip">
                    <div class="border-b border-dashed border-slate-600 w-full h-1/2"></div>
                </div>

                {{-- BAGIAN BAWAH (QR & TOTAL) --}}
                <div class="p-6 bg-slate-900 text-center">
                    
                    {{-- QR Code --}}
                    <div class="bg-white p-2 rounded-lg inline-block mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=FILMPASS-{{ $booking->id }}-{{ $booking->created_at->timestamp }}" 
                             alt="QR Code" 
                             class="w-32 h-32 object-contain">
                    </div>
                    
                    <p class="text-lg font-mono font-bold text-white tracking-widest mb-1">
                        FP-{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}
                    </p>
                    <p class="text-xs text-gray-500 mb-6">Scan kode ini di pintu masuk studio.</p>
                    
                    {{-- Rincian Bayar Kecil --}}
                    <div class="border-t border-slate-800 pt-4 flex justify-between items-center text-sm">
                        <span class="text-gray-400">Status Pembayaran</span>
                        <span class="font-bold text-green-500 uppercase text-xs bg-green-500/10 px-2 py-1 rounded">
                            {{ $booking->payment_status == 'paid' ? 'LUNAS' : $booking->payment_status }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center mt-2 text-sm">
                        <span class="text-gray-400">Total Bayar</span>
                        <span class="font-bold text-white">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                </div>
            </div> 
            {{-- End E-TICKET CARD --}}

            {{-- Tombol Aksi --}}
            <div class="max-w-md mx-auto mt-8 flex flex-col sm:flex-row justify-center gap-4 no-print">
                 <a href="{{ route('riwayat') }}" class="px-6 py-3 rounded-xl font-medium border border-slate-600 text-gray-300 hover:bg-slate-800 hover:text-white transition-colors flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Kembali ke Riwayat
                </a>
                <button onclick="window.print()" class="px-6 py-3 rounded-xl font-bold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-600/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    Cetak / Simpan PDF
                </button>
            </div>

        </div> 
    </main>

    @include('partials.footer')

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>Selesaikan Pembayaran - FilmPass</title>
    
    {{-- 
        MIDTRANS SNAP JS 
        Menggunakan logika dinamis untuk memilih URL Sandbox atau Production
    --}}
    @php
        $isProduction = config('services.midtrans.is_production', false);
        $snapUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    
    <script type="text/javascript"
            src="{{ $snapUrl }}"
            data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-inter">

    {{-- Header Simple --}}
    <header class="bg-white shadow-sm py-4">
        <div class="max-w-3xl mx-auto px-6 flex items-center justify-center relative">
            <h1 class="text-xl font-bold text-indigo-900">Pembayaran</h1>
        </div>
    </header>

    <main class="py-12">
        <div class="max-w-md mx-auto px-6">
            
            {{-- Card Detail Pembayaran --}}
            <div class="bg-white rounded-2xl shadow-xl border border-indigo-50 overflow-hidden">
                
                {{-- Header Card --}}
                <div class="bg-indigo-600 p-6 text-center text-white">
                    <p class="text-indigo-100 text-sm mb-1">Total Pembayaran</p>
                    <h2 class="text-3xl font-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h2>
                    <div class="mt-4 inline-block px-3 py-1 bg-indigo-500 rounded-full text-xs font-mono">
                        Order ID: {{ $booking->id }}
                    </div>
                </div>

                {{-- Body Card --}}
                <div class="p-6 space-y-4">
                    <div class="flex items-start space-x-4">
                        {{-- Poster Film --}}
                        <img src="{{ asset('storage/' . $booking->schedule->film->poster_path) }}" 
                             class="w-16 h-24 object-cover rounded shadow-sm bg-gray-200">
                        
                        {{-- Detail Info --}}
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $booking->schedule->film->title }}</h3>
                            <p class="text-sm text-slate-500">{{ $booking->schedule->studio->name }}</p>
                            <p class="text-sm text-slate-500">
                                {{ $booking->schedule->start_time->format('d M Y, H:i') }}
                            </p>
                            <p class="text-xs text-indigo-600 font-semibold mt-1">
                                {{ $booking->seat_count }} Tiket ({{ $booking->seats }})
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer Card (Tombol) --}}
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <button id="pay-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5 flex justify-center items-center">
                        <span>Bayar Sekarang</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-4">
                        Proses pembayaran aman didukung oleh Midtrans.
                    </p>
                </div>
            </div>

            {{-- Tombol Kembali --}}
            <div class="text-center mt-6">
                <a href="{{ route('riwayat') }}" class="text-sm text-slate-500 hover:text-slate-800">
                    Batalkan & Kembali ke Riwayat
                </a>
            </div>

        </div>
    </main>

    {{-- SCRIPT MIDTRANS --}}
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        
        // Fungsi Trigger Snap
        function triggerSnap() {
            window.snap.pay('{{ $snapToken }}', {
                // Jika pembayaran sukses/selesai di sisi Frontend
                onSuccess: function(result){
                    // Redirect ke detail riwayat, status akan menunggu update dari Webhook/Admin
                    window.location.href = "{{ route('riwayat.detail', $booking->id) }}";
                },
                // Jika pembayaran tertunda (misal: menunggu transfer VA/Indomaret)
                onPending: function(result){
                    window.location.href = "{{ route('riwayat.detail', $booking->id) }}";
                },
                // Jika terjadi error
                onError: function(result){
                    alert("Pembayaran gagal atau dibatalkan.");
                    console.log(result);
                },
                // Jika pop-up ditutup tanpa menyelesaikan pembayaran
                onClose: function(){
                    alert('Anda menutup jendela pembayaran sebelum menyelesaikan transaksi.');
                }
            });
        }

        // Event Listener Tombol Manual
        payButton.addEventListener('click', triggerSnap);

        // Auto Trigger Pop-up setelah 1 detik (UX yang lebih baik)
        setTimeout(() => {
            triggerSnap();
        }, 1000);
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>Checkout & Pembayaran</title>
</head>
<body class="bg-slate-50 text-slate-900 font-inter">

    {{-- Header Simple --}}
    <header class="bg-white shadow-sm py-4">
        <div class="max-w-3xl mx-auto px-6 flex items-center">
            <a href="{{ url()->previous() }}" class="text-slate-500 hover:text-slate-700 mr-4">
                <i data-lucide="arrow-left" class="w-6 h-6"></i>
            </a>
            <h1 class="text-xl font-bold">Konfirmasi & Pembayaran</h1>
        </div>
    </header>

    <main class="py-10">
        <div class="max-w-3xl mx-auto px-6">
            
            <form action="{{ route('booking.process') }}" method="POST">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                
                {{-- Kirim array kursi --}}
                @foreach($seats as $seat)
                    <input type="hidden" name="seats[]" value="{{ $seat }}">
                @endforeach

                <div class="grid gap-6">
                    
                    {{-- 1. Ringkasan Pesanan --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gray-50">
                            <h2 class="font-bold text-lg text-slate-800">Ringkasan Pesanan</h2>
                        </div>
                        <div class="p-6 flex gap-6">
                            {{-- Poster Kecil --}}
                            <img src="{{ asset('storage/' . $schedule->film->poster_path) }}" class="w-24 h-36 rounded-lg object-cover shadow-sm">
                            
                            <div class="flex-grow space-y-2">
                                <h3 class="text-xl font-bold text-indigo-900">{{ $schedule->film->title }}</h3>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i> {{ $schedule->studio->name }} ({{ $schedule->studio->type }})
                                </p>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i> {{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y, H:i') }}
                                </p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($seats as $seat)
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded font-bold text-sm border border-indigo-100">{{ $seat }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Rincian Harga --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="font-bold text-lg text-slate-800 mb-4">Rincian Pembayaran</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tiket ({{ count($seats) }}x)</span>
                                <span>Rp {{ number_format($schedule->price * count($seats), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Biaya Layanan</span>
                                <span>Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-3 mt-3 flex justify-between items-center">
                                <span class="font-bold text-lg">Total Bayar</span>
                                <span class="font-bold text-xl text-orange-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Metode Pembayaran --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="font-bold text-lg text-slate-800 mb-4">Pilih Metode Pembayaran</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition hover:border-indigo-300 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="payment_method" value="qris" class="w-5 h-5 text-indigo-600" checked>
                                <div class="ml-4 flex-grow">
                                    <span class="font-bold block text-slate-800">QRIS</span>
                                    <span class="text-xs text-gray-500">Scan dengan GoPay, OVO, Dana, dll</span>
                                </div>
                                <i data-lucide="qr-code" class="w-6 h-6 text-gray-400"></i>
                            </label>

                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition hover:border-indigo-300 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="w-5 h-5 text-indigo-600">
                                <div class="ml-4 flex-grow">
                                    <span class="font-bold block text-slate-800">Transfer Bank</span>
                                    <span class="text-xs text-gray-500">BCA, Mandiri, BNI, BRI</span>
                                </div>
                                <i data-lucide="credit-card" class="w-6 h-6 text-gray-400"></i>
                            </label>
                        </div>
                    </div>

                    {{-- Tombol Bayar --}}
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-1">
                        Bayar Sekarang
                    </button>

                </div>
            </form>
        </div>
    </main>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
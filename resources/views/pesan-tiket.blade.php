<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>Pesan Tiket - {{ $film->title }}</title>
    <style>
        body { background-color: #0f172a; }
        .bg-dark-card { background-color: #1e293b; }
    </style>
</head>

<body class="text-white min-h-screen flex flex-col font-inter">

    @include('partials.header')

    <main class="flex-grow pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="mb-6">
                <a href="{{ route('movies.index') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition-colors">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                    Kembali ke Daftar Film
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-dark-card rounded-xl shadow-lg overflow-hidden sticky top-24">
                        <img src="{{ $film->poster_url }}" alt="{{ $film->title }}" class="w-full h-96 object-cover">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold mb-2">{{ $film->title }}</h2>
                            <div class="flex items-center text-gray-400 mb-4">
                                <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                                <span class="text-sm">{{ $film->duration_minutes }} Menit • {{ $film->rating ?? 'SU' }}</span>
                            </div>
                            <p class="text-gray-300 text-sm mb-4 line-clamp-4">{{ $film->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-dark-card rounded-xl shadow-lg p-8">
                        <h1 class="text-3xl font-bold mb-8">Pilih Jadwal</h1>

                        <form action="{{ route('ticket.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-8">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @forelse ($schedules as $schedule)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="schedule_id" value="{{ $schedule->id }}" required 
                                                   class="peer sr-only" 
                                                   data-price="{{ $schedule->price }}"
                                                   onchange="updateTotal()">
                                            
                                            <div class="p-4 rounded-lg border-2 border-slate-700 bg-slate-800 hover:border-indigo-500 peer-checked:border-indigo-500 peer-checked:bg-indigo-900/20 transition-all">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="font-bold text-white">{{ $schedule->studio->name }}</span>
                                                    <span class="text-xs font-semibold bg-indigo-600 px-2 py-1 rounded">{{ $schedule->studio->type }}</span>
                                                </div>
                                                <div class="flex justify-between items-center text-sm text-gray-400">
                                                    <div class="flex items-center">
                                                        <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                                        {{ $schedule->start_time->format('d M Y') }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                        {{ $schedule->start_time->format('H:i') }}
                                                    </div>
                                                </div>
                                                <div class="mt-3 pt-3 border-t border-slate-700 flex justify-between items-center">
                                                    <span class="text-sm text-gray-400">Harga</span>
                                                    <span class="font-bold text-indigo-400">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="col-span-full text-gray-400">Belum ada jadwal tersedia.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mb-8 border-t border-slate-700 pt-6">
                                <label class="block text-sm font-semibold mb-3 flex items-center">
                                    <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                                    Jumlah Tiket
                                </label>
                                <div class="flex items-center space-x-4">
                                    <button type="button" onclick="changeSeats(-1)" class="w-10 h-10 rounded-lg bg-slate-700 hover:bg-slate-600 flex items-center justify-center text-xl font-bold">-</button>
                                    <input type="number" name="seat_count" id="seat_count" value="1" min="1" max="10" readonly
                                           class="w-20 bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-center font-bold text-lg focus:outline-none">
                                    <button type="button" onclick="changeSeats(1)" class="w-10 h-10 rounded-lg bg-slate-700 hover:bg-slate-600 flex items-center justify-center text-xl font-bold">+</button>
                                </div>
                            </div>

                            <div class="bg-slate-800 rounded-lg p-6 mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-400">Harga Satuan</span>
                                    <span id="pricePerTicket" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-gray-400">Jumlah</span>
                                    <span id="ticketCountDisplay">1 Tiket</span>
                                </div>
                                <div class="border-t border-slate-700 pt-4 mt-2 flex justify-between items-center">
                                    <span class="text-xl font-bold">Total Bayar</span>
                                    <span id="totalPrice" class="text-2xl font-bold text-indigo-400">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" id="submitBtn" disabled
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-700 disabled:cursor-not-allowed text-white font-bold py-4 rounded-lg transition duration-150 flex items-center justify-center space-x-2 shadow-lg">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                <span>Konfirmasi Pemesanan</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        function changeSeats(amount) {
            const input = document.getElementById('seat_count');
            let val = parseInt(input.value) + amount;
            if (val < 1) val = 1;
            if (val > 10) val = 10;
            input.value = val;
            updateTotal();
        }

        function updateTotal() {
            const selectedSchedule = document.querySelector('input[name="schedule_id"]:checked');
            const seatCount = parseInt(document.getElementById('seat_count').value);
            const submitBtn = document.getElementById('submitBtn');
            
            if (selectedSchedule) {
                const price = parseInt(selectedSchedule.dataset.price);
                const total = price * seatCount;

                // Update UI
                document.getElementById('pricePerTicket').textContent = 'Rp ' + price.toLocaleString('id-ID');
                document.getElementById('ticketCountDisplay').textContent = seatCount + ' Tiket';
                document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
                
                // Enable button
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-slate-700');
                submitBtn.classList.add('bg-indigo-600');
            } else {
                // Reset if no schedule selected
                document.getElementById('totalPrice').textContent = 'Rp 0';
                submitBtn.disabled = true;
            }
        }

        // Initialize icons
        lucide.createIcons();
    </script>
</body>
</html>
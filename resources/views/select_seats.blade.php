<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>Pilih Kursi - {{ $schedule->film->title }}</title>
    <style>
        .screen-curve {
            height: 60px;
            width: 100%;
            background: linear-gradient(to bottom, rgba(255,255,255,0.1), transparent);
            border-top: 4px solid #6366f1; /* indigo-500 */
            border-radius: 50% 50% 0 0 / 20px 20px 0 0;
            box-shadow: 0 -10px 20px rgba(99, 102, 241, 0.3);
        }
        .seat {
            transition: all 0.2s;
        }
        .seat-checkbox:checked + label {
            background-color: #f97316; /* orange-500 */
            color: white;
            border-color: #f97316;
        }
        .seat-booked {
            background-color: #334155; /* slate-700 */
            color: #64748b;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-slate-900 text-white font-inter">

    @include('partials.header')

    <main class="pt-24 pb-16 min-h-screen">
        <div class="max-w-5xl mx-auto px-6">
            
            {{-- Header Steps --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $schedule->film->title }}</h1>
                    <p class="text-gray-400 text-sm">
                        {{ $schedule->studio->name }} • {{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-400">Langkah 1 dari 2</div>
                    <div class="font-bold text-indigo-400">Pilih Kursi</div>
                </div>
            </div>

            {{-- LAYAR BIOSKOP --}}
            <div class="mb-10 text-center">
                <div class="w-3/4 mx-auto screen-curve mb-4"></div>
                <p class="text-gray-500 text-xs uppercase tracking-widest">Layar Bioskop</p>
            </div>

            {{-- GRID KURSI --}}
            <div class="overflow-x-auto pb-8">
                <div class="flex flex-col gap-3 items-center min-w-[600px]">
                    @foreach($rows as $row)
                        <div class="flex gap-3">
                            {{-- Label Baris Kiri --}}
                            <div class="w-8 flex items-center justify-center font-bold text-gray-500">{{ $row['label'] }}</div>
                            
                            <div class="flex gap-2">
                                @foreach($row['seats'] as $seat)
                                    @if($seat['status'] == 'booked')
                                        {{-- Kursi Terisi --}}
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-t-lg rounded-b-md flex items-center justify-center text-xs font-bold seat-booked select-none" title="Terisi">
                                            {{ substr($seat['code'], 1) }}
                                        </div>
                                    @else
                                        {{-- Kursi Kosong (Bisa Dipilih) --}}
                                        <div class="relative">
                                            <input type="checkbox" 
                                                   id="seat-{{ $seat['code'] }}" 
                                                   class="seat-checkbox absolute opacity-0 w-full h-full cursor-pointer z-10"
                                                   value="{{ $seat['code'] }}"
                                                   onchange="updateSelection(this)">
                                            <label for="seat-{{ $seat['code'] }}" 
                                                   class="w-8 h-8 sm:w-10 sm:h-10 rounded-t-lg rounded-b-md bg-slate-800 border border-slate-600 flex items-center justify-center text-xs font-bold text-gray-300 hover:border-indigo-500 hover:text-indigo-400 cursor-pointer transition-colors">
                                                {{ substr($seat['code'], 1) }}
                                            </label>
                                        </div>
                                    @endif
                                    
                                    {{-- Jarak untuk lorong tengah (opsional, misal setelah kursi 10) --}}
                                    @if($loop->iteration == 10)
                                        <div class="w-8"></div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Label Baris Kanan --}}
                            <div class="w-8 flex items-center justify-center font-bold text-gray-500">{{ $row['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- LEGENDA --}}
            <div class="flex justify-center gap-6 mb-8 text-sm text-gray-400">
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-slate-800 border border-slate-600 rounded"></div> Tersedia</div>
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-orange-500 rounded"></div> Dipilih</div>
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-slate-700 rounded"></div> Terisi</div>
            </div>

            {{-- BOTTOM BAR --}}
            <div class="bg-white fixed bottom-0 left-0 w-full p-4 shadow-lg z-50 border-t border-gray-200 text-slate-900">
                <div class="max-w-5xl mx-auto flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Kursi Dipilih:</p>
                        <p class="font-bold text-lg text-indigo-700" id="selected-seats-display">-</p>
                    </div>
                    <div class="text-right mr-6">
                        <p class="text-sm text-gray-500">Total Harga:</p>
                        <p class="font-bold text-lg" id="total-price-display">Rp 0</p>
                    </div>
                    
                    <form action="{{ route('booking.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                        <input type="hidden" name="selected_seats" id="selected-seats-input">
                        
                        <button type="submit" id="checkout-btn" disabled 
                                class="bg-gray-400 text-white px-8 py-3 rounded-lg font-bold cursor-not-allowed transition-colors">
                            Lanjut Bayar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script>
        const pricePerSeat = {{ $schedule->price }};
        let selectedSeats = [];

        function updateSelection(checkbox) {
            const seatCode = checkbox.value;

            if (checkbox.checked) {
                if (selectedSeats.length >= 8) {
                    alert('Maksimal 8 kursi per transaksi!');
                    checkbox.checked = false;
                    return;
                }
                selectedSeats.push(seatCode);
            } else {
                selectedSeats = selectedSeats.filter(s => s !== seatCode);
            }

            renderBottomBar();
        }

        function renderBottomBar() {
            const seatsDisplay = document.getElementById('selected-seats-display');
            const priceDisplay = document.getElementById('total-price-display');
            const checkoutBtn = document.getElementById('checkout-btn');
            const seatsInput = document.getElementById('selected-seats-input');

            // Update Text Kursi
            seatsDisplay.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '-';
            seatsInput.value = selectedSeats.join(',');

            // Update Harga
            const total = selectedSeats.length * pricePerSeat;
            priceDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');

            // Update Tombol
            if (selectedSeats.length > 0) {
                checkoutBtn.disabled = false;
                checkoutBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                checkoutBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            } else {
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                checkoutBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            }
        }
    </script>
</body>
</html>
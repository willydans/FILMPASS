<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pesan Tiket - {{ $movie['title'] }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
    body {
      font-family: 'Inter', sans-serif;
      background-color: #0f172a;
    }
    .bg-dark-card {
      background-color: #1e293b;
    }
    .seat {
      width: 40px;
      height: 40px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .seat.available:hover {
      transform: scale(1.1);
      background-color: #6366f1;
    }
    .seat.selected {
      background-color: #4f46e5;
    }
    .seat.booked {
      background-color: #475569;
      cursor: not-allowed;
    }
  </style>
</head>

<body class="text-white min-h-screen flex flex-col">

  {{-- HEADER --}}
  @include('partials.header')

  <!-- MAIN -->
  <main class="flex-grow pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-6">
      <!-- Kembali -->
      <div class="mb-6">
        <a href="/dashboard" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition-colors">
          <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
          Kembali ke Daftar Film
        </a>
      </div>

      @if(session('success'))
      <div class="mb-6 bg-green-600 text-white px-6 py-4 rounded-lg flex items-center">
        <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
        <span>{{ session('success') }}</span>
      </div>
      @endif

      <!-- Grid Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Informasi Film -->
        <div class="lg:col-span-1">
          <div class="bg-dark-card rounded-xl shadow-lg overflow-hidden sticky top-24">
            <img src="{{ $movie['image'] }}" alt="{{ $movie['title'] }}" class="w-full h-80 object-cover">
            <div class="p-6">
              <h2 class="text-2xl font-bold mb-2">{{ $movie['title'] }}</h2>
              <div class="flex items-center text-gray-400 mb-4">
                <i data-lucide="film" class="w-4 h-4 mr-2"></i>
                <span class="text-sm">{{ $movie['genre'] }} • {{ $movie['duration'] }}</span>
              </div>
              <p class="text-gray-300 text-sm mb-4">{{ $movie['description'] }}</p>
              <div class="border-t border-gray-700 pt-4">
                <div class="flex justify-between items-center">
                  <span class="text-gray-400">Harga Tiket</span>
                  <span class="text-2xl font-bold text-indigo-400">Rp {{ number_format($movie['price'], 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Pemesanan -->
        <div class="lg:col-span-2">
          <div class="bg-dark-card rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-8">Pemesanan Tiket</h1>

            <form action="{{ route('pesan.tiket.store') }}" method="POST" id="bookingForm">
              @csrf
              <input type="hidden" name="movie_title" value="{{ $movie['title'] }}">

              <!-- Pilih Bioskop -->
              <div class="mb-6">
                <label class="block text-sm font-semibold mb-3 flex items-center">
                  <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                  Pilih Bioskop
                </label>
                <select name="cinema" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500">
                  <option value="">-- Pilih Bioskop --</option>
                  @foreach($cinemas as $cinema)
                  <option value="{{ $cinema }}">{{ $cinema }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Pilih Tanggal -->
              <div class="mb-6">
                <label class="block text-sm font-semibold mb-3 flex items-center">
                  <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                  Pilih Tanggal
                </label>
                <div class="grid grid-cols-3 gap-3">
                  @foreach($schedules as $date => $times)
                  <label class="cursor-pointer">
                    <input type="radio" name="date" value="{{ $date }}" class="peer hidden" required onchange="updateTimes('{{ $date }}')">
                    <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-4 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-900/30 hover:border-gray-600 transition">
                      <div class="text-xs text-gray-400">{{ date('D', strtotime($date)) }}</div>
                      <div class="text-lg font-bold">{{ date('d', strtotime($date)) }}</div>
                      <div class="text-xs text-gray-400">{{ date('M', strtotime($date)) }}</div>
                    </div>
                  </label>
                  @endforeach
                </div>
              </div>

              <!-- Pilih Jam -->
              <div class="mb-6">
                <label class="block text-sm font-semibold mb-3 flex items-center">
                  <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                  Pilih Jam Tayang
                </label>
                <div id="timeSlots" class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                  <div class="text-gray-400 text-sm col-span-full text-center py-4">Pilih tanggal terlebih dahulu</div>
                </div>
              </div>

              <!-- Jumlah Tiket -->
              <div class="mb-6">
                <label class="block text-sm font-semibold mb-3 flex items-center">
                  <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                  Jumlah Tiket
                </label>
                <input type="number" name="seats" min="1" max="10" value="1" required 
                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500"
                  oninput="calculateTotal()">
              </div>

              <!-- Data Pemesan -->
              <div class="border-t border-gray-700 pt-6 mb-6">
                <h3 class="text-xl font-bold mb-4">Data Pemesan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-sm font-semibold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required 
                      class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500"
                      placeholder="John Doe">
                  </div>
                  <div>
                    <label class="block text-sm font-semibold mb-2">Email</label>
                    <input type="email" name="email" required 
                      class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500"
                      placeholder="john@example.com">
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-semibold mb-2">No. Telepon</label>
                  <input type="tel" name="phone" required 
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-indigo-500"
                    placeholder="08123456789">
                </div>
              </div>

              <!-- Total -->
              <div class="bg-gray-800 rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-400">Harga per tiket</span>
                  <span>Rp {{ number_format($movie['price'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-400">Jumlah tiket</span>
                  <span id="ticketCount">1</span>
                </div>
                <div class="border-t border-gray-700 pt-4 mt-4">
                  <div class="flex justify-between items-center">
                    <span class="text-xl font-bold">Total Bayar</span>
                    <span class="text-2xl font-bold text-indigo-400" id="totalPrice">Rp {{ number_format($movie['price'], 0, ',', '.') }}</span>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-lg transition duration-150 flex items-center justify-center space-x-2 shadow-lg">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                <span>Konfirmasi Pemesanan</span>
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </main>

  {{-- FOOTER --}}
  @include('partials.footer')

  <script>
    const schedules = @json($schedules);
    const basePrice = {{ $movie['price'] }};

    function updateTimes(date) {
      const timeSlotsContainer = document.getElementById('timeSlots');
      const times = schedules[date];
      
      if (times && times.length > 0) {
        timeSlotsContainer.innerHTML = times.map(time => `
          <label class="cursor-pointer">
            <input type="radio" name="time" value="${time}" class="peer hidden" required>
            <div class="bg-gray-800 border-2 border-gray-700 rounded-lg p-3 text-center peer-checked:border-indigo-500 peer-checked:bg-indigo-900/30 hover:border-gray-600 transition">
              <div class="font-semibold">${time}</div>
            </div>
          </label>
        `).join('');
      } else {
        timeSlotsContainer.innerHTML = '<div class="text-gray-400 text-sm col-span-full text-center py-4">Tidak ada jadwal tersedia</div>';
      }
    }

    function calculateTotal() {
      const seats = document.querySelector('input[name="seats"]').value || 1;
      const total = basePrice * seats;
      
      document.getElementById('ticketCount').textContent = seats;
      document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    window.onload = () => {
      lucide.createIcons();
    };
  </script>
</body>
</html>
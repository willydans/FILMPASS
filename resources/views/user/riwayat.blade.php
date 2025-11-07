<!DOCTYPE html>
<html lang="id">
<head>
    {{-- 1. MEMANGGIL ELEMEN HEAD (TERMASUK TAILWIND & ALPINE.JS CDN) --}}
    @include('partials.head')
</head>
<body class="bg-slate-900">
    
    {{-- 2. MEMANGGIL ELEMEN HEADER (NAVIGASI) --}}
    @include('partials.header')

    {{-- KONTEN UTAMA RIWAYAT --}}
    <main class="bg-gray-50 text-gray-900 pt-32 pb-16">
    <div class="max-w-4xl mx-auto px-4 text-center">

      <!-- Judul Halaman -->
      <h1 class="text-3xl font-bold text-blue-800 mb-2">Riwayat Pemesanan</h1>
      <p class="text-gray-600 mb-10">Kelola tiket dan cari film favorit Anda</p>

      <!-- Cari Film -->
      <div class="bg-white shadow-md rounded-2xl p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Cari Film</h2>
        <p class="text-sm text-gray-500 mb-4">Temukan film favorit Anda di FilmPass</p>

        <div class="flex flex-col sm:flex-row justify-center gap-3">
          <input type="text" placeholder="Cari berdasarkan judul film atau genre..." 
                class="w-full sm:w-2/3 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
          <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
            Cari Film
          </button>
        </div>
      </div>

      <!-- Lihat Riwayat Pemesanan -->
      <div class="bg-white shadow-md rounded-2xl p-6 mb-8">
        <div class="flex flex-col items-center">
          <div class="text-5xl text-blue-500 mb-3">👤</div>
          <h2 class="text-lg font-semibold text-gray-700 mb-1">Lihat Riwayat Pemesanan Anda</h2>
          <p class="text-sm text-gray-500 mb-4">Login untuk melihat tiket dan riwayat pemesanan Anda</p>
          <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg">
            Login Sekarang
          </button>
        </div>
      </div>

      <!-- Jelajahi FilmPass -->
      <div class="bg-white shadow-md rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-6">Jelajahi FilmPass</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

          <div class="border rounded-xl p-4 hover:bg-gray-50">
            <div class="text-3xl text-blue-600 mb-2">🏠</div>
            <h3 class="font-semibold">Beranda</h3>
            <p class="text-sm text-gray-500">Kembali ke halaman utama</p>
          </div>

          <div class="border rounded-xl p-4 hover:bg-gray-50">
            <div class="text-3xl text-blue-600 mb-2">🎬</div>
            <h3 class="font-semibold">Semua Film</h3>
            <p class="text-sm text-gray-500">Lihat koleksi film lengkap</p>
          </div>

          <div class="border rounded-xl p-4 hover:bg-gray-50">
            <div class="text-3xl text-blue-600 mb-2">🔑</div>
            <h3 class="font-semibold">Login</h3>
            <p class="text-sm text-gray-500">Masuk untuk melihat tiket</p>
          </div>

        </div>
      </div>

    </div>
  </main>

  {{-- 3. MEMANGGIL ELEMEN FOOTER --}}
    @include('partials.footer')

</body>
</html>
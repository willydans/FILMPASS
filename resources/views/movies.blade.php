<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Film - FilmPass</title>
  
  {{-- Gunakan partial head Anda agar konsisten --}}
  @include('partials.head')

  <style>
    body {
      background-color: #0f172a; /* slate-900 */
    }
    .bg-dark-card {
      background-color: #1e293b; /* slate-800 */
    }
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
      -webkit-line-clamp: 2;
    }
  </style>
</head>

<body class="text-white min-h-screen flex flex-col font-inter">

  {{-- HEADER --}}
  @include('partials.header')

  <main class="flex-grow pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-6">
      
      <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition-colors">
          <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
          Kembali ke Dashboard
        </a>
      </div>

      <section class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 space-y-4 md:space-y-0">
        <h1 class="text-3xl font-bold text-white md:mr-10">Temukan Film Favoritmu</h1>

        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
          
          <div class="relative w-full sm:w-80">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            <input id="searchInput" type="text" placeholder="Cari film..." 
                   class="pl-10 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-gray-400">
          </div>

          <div class="relative">
            <select id="genreSelect" class="appearance-none block w-full bg-slate-800 border border-slate-700 py-2 pl-4 pr-8 rounded-lg leading-tight focus:outline-none focus:border-indigo-500 cursor-pointer text-gray-300">
              <option value="Semua Genre">Semua Genre</option>
              {{-- Kita bisa ambil genre unik dari koleksi film --}}
              @foreach($films->pluck('rating')->unique() as $rating)
                  <option value="{{ $rating }}">{{ $rating }}</option>
              @endforeach
              {{-- Atau opsi manual jika kolom genre belum ada --}}
              <option value="Action">Action</option>
              <option value="Drama">Drama</option>
              <option value="Comedy">Comedy</option>
            </select>
            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
          </div>

        </div>
      </section>

      <div class="mb-6 text-gray-400 text-sm" id="resultCount">
        Menampilkan {{ $films->count() }} film
      </div>

      <section id="movieGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        
        @forelse($films as $film)
            {{-- 
               Kita tambahkan data attributes (data-title, data-genre) 
               agar JavaScript bisa melakukan filter tanpa reload halaman.
            --}}
            <div class="movie-card bg-dark-card rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 flex flex-col"
                 data-title="{{ strtolower($film->title) }}"
                 data-genre="{{ $film->rating ?? 'Umum' }}">
              
              {{-- Poster Film --}}
              <img src="{{ $film->poster_url }}" alt="Poster {{ $film->title }}" class="w-full h-64 object-cover">
              
              <div class="p-4 flex flex-col flex-grow">
                <h3 class="text-lg font-bold text-white mb-1 line-clamp-1" title="{{ $film->title }}">
                    {{ $film->title }}
                </h3>
                
                <p class="text-sm text-gray-400 mb-2">
                    {{ $film->rating ?? 'Umum' }} • {{ $film->duration_minutes }} menit
                </p>
                
                <p class="text-sm text-gray-300 line-clamp-2 mb-4 flex-grow">
                    {{ $film->description }}
                </p>
                
                {{-- Tombol Pesan Tiket --}}
                <a href="{{ route('ticket.create', $film->id) }}" 
                   class="w-full mt-auto bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md flex items-center justify-center space-x-2">
                    <i data-lucide="ticket" class="w-4 h-4"></i>
                    <span>Pesan Tiket</span>
                </a>
              </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 py-10">
                Belum ada film yang tersedia saat ini.
            </p>
        @endforelse

      </section>

      {{-- Pesan "Tidak Ditemukan" (Tersembunyi by default) --}}
      <p id="noResultsMessage" class="hidden col-span-full text-center text-gray-500 py-10">
          Tidak ada film yang cocok dengan pencarian Anda.
      </p>

    </div>
  </main>

  {{-- FOOTER --}}
  @include('partials.footer')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Ikon
        lucide.createIcons();

        const searchInput = document.getElementById('searchInput');
        const genreSelect = document.getElementById('genreSelect');
        const movieCards = document.querySelectorAll('.movie-card');
        const resultCount = document.getElementById('resultCount');
        const noResultsMessage = document.getElementById('noResultsMessage');

        function filterMovies() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedGenre = genreSelect.value;
            let visibleCount = 0;

            movieCards.forEach(card => {
                const title = card.getAttribute('data-title');
                const genre = card.getAttribute('data-genre');

                const matchesSearch = title.includes(searchTerm);
                // Logika filter genre (sesuaikan jika kolom genre Anda berbeda)
                const matchesGenre = selectedGenre === 'Semua Genre' || genre === selectedGenre;

                if (matchesSearch && matchesGenre) {
                    card.style.display = 'flex'; // Tampilkan (flex karena layout flex-col)
                    visibleCount++;
                } else {
                    card.style.display = 'none'; // Sembunyikan
                }
            });

            // Update UI text
            resultCount.textContent = `Menampilkan ${visibleCount} film`;

            // Tampilkan pesan jika tidak ada hasil
            if (visibleCount === 0) {
                noResultsMessage.classList.remove('hidden');
            } else {
                noResultsMessage.classList.add('hidden');
            }
        }

        // Event Listeners
        searchInput.addEventListener('input', filterMovies);
        genreSelect.addEventListener('change', filterMovies);
    });
  </script>

</body>
</html>
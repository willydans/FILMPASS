<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Film - FilmPass</title>
  
  {{-- Gunakan partial head Anda --}}
  @include('partials.head')

  <style>
    body {
      background-color: #0f172a; /* slate-900 */
    }
    .bg-dark-card {
      background-color: #1e293b; /* slate-800 */
    }
    /* Custom scrollbar for aesthetics */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #0f172a;
    }
    ::-webkit-scrollbar-thumb {
      background: #334155;
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #475569;
    }
  </style>
</head>

<body class="text-white min-h-screen flex flex-col font-inter">

  {{-- HEADER --}}
  @include('partials.header')

  <main class="flex-grow pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-6">
      
      {{-- Tombol Kembali --}}
      <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mr-2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
          Kembali ke Dashboard
        </a>
      </div>

      {{-- LOGIKA PHP UNTUK MENGAMBIL GENRE & RATING UNIK --}}
      @php
          // 1. Ambil Genre Unik
          $allGenres = $films->pluck('genre')
              ->flatMap(function ($item) {
                  return explode(', ', $item);
              })
              ->unique()
              ->sort()
              ->values();

          // 2. Ambil Rating Unik (SU, 13+, 17+, dll)
          $allRatings = $films->pluck('rating')->unique()->sort()->values();
      @endphp

      {{-- FILTER SECTION --}}
      <section class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 space-y-4 lg:space-y-0">
        <h1 class="text-3xl font-bold text-white md:mr-10">Temukan Film Favoritmu</h1>

        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
          
          {{-- Search Input --}}
          <div class="relative flex-grow sm:w-64">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input id="searchInput" type="text" placeholder="Cari judul film..." 
                   class="pl-10 pr-4 py-2 bg-slate-800 border border-slate-700 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-gray-400 transition-all">
          </div>

          {{-- Genre Select --}}
          <div class="relative sm:w-48">
            <select id="genreSelect" class="appearance-none block w-full bg-slate-800 border border-slate-700 py-2 pl-4 pr-10 rounded-lg leading-tight focus:outline-none focus:border-indigo-500 cursor-pointer text-gray-300 transition-all">
              <option value="Semua Genre">Semua Genre</option>
              @foreach($allGenres as $genre)
                  <option value="{{ $genre }}">{{ $genre }}</option>
              @endforeach
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"><path d="m6 9 6 6 6-6"/></svg>
          </div>

          {{-- Rating Select (BARU) --}}
          <div class="relative sm:w-40">
            <select id="ratingSelect" class="appearance-none block w-full bg-slate-800 border border-slate-700 py-2 pl-4 pr-10 rounded-lg leading-tight focus:outline-none focus:border-indigo-500 cursor-pointer text-gray-300 transition-all">
              <option value="Semua Umur">Semua Umur</option>
              @foreach($allRatings as $rating)
                  <option value="{{ $rating }}">{{ $rating }}</option>
              @endforeach
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"><path d="m6 9 6 6 6-6"/></svg>
          </div>

        </div>
      </section>

      {{-- Counter Hasil --}}
      <div class="mb-6 text-gray-400 text-sm flex justify-between items-center">
        <span id="resultCount">Menampilkan {{ $films->count() }} film</span>
      </div>

      {{-- MOVIE GRID --}}
      <section id="movieGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        
        @forelse($films as $film)
            <div class="movie-card bg-dark-card rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 flex flex-col group border border-slate-700 hover:border-indigo-500/50"
                 data-title="{{ strtolower($film->title) }}"
                 data-genre="{{ strtolower($film->genre) }}"
                 data-rating="{{ $film->rating }}"> {{-- TAMBAHAN DATA RATING --}}
              
              {{-- Poster Film --}}
              <div class="relative aspect-[2/3] overflow-hidden">
                  <img src="{{ $film->poster_path ? asset('storage/' . $film->poster_path) : 'https://placehold.co/400x600/1E293B/FFF?text=No+Poster' }}" 
                       alt="Poster {{ $film->title }}" 
                       class="w-full h-full object-cover group-hover:brightness-75 transition-all duration-300">
                  
                   {{-- Rating Badge Overlay --}}
                   <div class="absolute top-2 right-2 bg-black/70 backdrop-blur-sm text-yellow-400 text-xs font-bold px-2 py-1 rounded flex items-center border border-white/10">
                      <span class="mr-1">⭐</span> {{ $film->rating }}
                  </div>
              </div>
              
              <div class="p-4 flex flex-col flex-grow">
                <h3 class="text-lg font-bold text-white mb-1 line-clamp-1 group-hover:text-indigo-400 transition-colors" title="{{ $film->title }}">
                    {{ $film->title }}
                </h3>
                
                <div class="flex flex-wrap gap-2 mb-3 text-xs text-gray-400">
                   {{-- Tampilkan Genre (maksimal 2 agar rapi) --}}
                   @foreach(array_slice(explode(', ', $film->genre), 0, 2) as $g)
                      <span class="bg-slate-700 px-2 py-0.5 rounded">{{ $g }}</span>
                   @endforeach
                   <span>• {{ $film->duration_minutes }} m</span>
                </div>
                
                <p class="text-sm text-gray-300 line-clamp-2 mb-4 flex-grow text-justify">
                    {{ $film->description }}
                </p>
                
                {{-- Tombol Pesan Tiket --}}
                <a href="{{ route('ticket.create', $film->id) }}" 
                   class="w-full mt-auto bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition duration-150 shadow-lg shadow-indigo-600/20 flex items-center justify-center space-x-2 group-hover:translate-y-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                    <span>Pesan Tiket</span>
                </a>
              </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-500 bg-slate-800/50 rounded-xl border border-dashed border-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 mb-4 opacity-50"><path d="M19.82 2H4.18C2.97 2 2 2.97 2 4.18v15.64C2 21.03 2.97 22 4.18 22h15.64c1.21 0 2.18-.97 2.18-2.18V4.18C22 2.97 21.03 2 19.82 2Z"/><path d="M7 2v20"/><path d="M17 2v20"/><path d="M2 12h20"/><path d="M2 7h5"/><path d="M2 17h5"/><path d="M17 17h5"/><path d="M17 7h5"/></svg>
                <p class="text-lg">Belum ada film yang tersedia saat ini.</p>
            </div>
        @endforelse

      </section>

      {{-- Pesan "Tidak Ditemukan" --}}
      <div id="noResultsMessage" class="hidden col-span-full text-center py-20 bg-slate-800/50 rounded-xl border border-dashed border-slate-700">
        <p class="text-gray-400 text-lg">Tidak ada film yang cocok dengan kriteria pencarian Anda.</p>
        <button onclick="resetFilters()" class="mt-4 text-indigo-400 hover:underline">Reset Filter</button>
      </div>

    </div>
  </main>

  {{-- FOOTER --}}
  @include('partials.footer')

  {{-- SCRIPTS --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const searchInput = document.getElementById('searchInput');
        const genreSelect = document.getElementById('genreSelect');
        const ratingSelect = document.getElementById('ratingSelect'); // Selector baru
        const movieCards = document.querySelectorAll('.movie-card');
        const resultCount = document.getElementById('resultCount');
        const noResultsMessage = document.getElementById('noResultsMessage');

        function filterMovies() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedGenre = genreSelect.value.toLowerCase();
            const selectedRating = ratingSelect.value; // Rating case-sensitive tidak masalah (misal SU, 13+)
            
            let visibleCount = 0;

            movieCards.forEach(card => {
                const title = card.getAttribute('data-title'); 
                const filmGenres = card.getAttribute('data-genre'); 
                const filmRating = card.getAttribute('data-rating'); // Ambil data rating dari kartu

                // 1. Cek Judul
                const matchesSearch = title.includes(searchTerm);
                
                // 2. Cek Genre
                const matchesGenre = selectedGenre === 'semua genre' || filmGenres.includes(selectedGenre);
                
                // 3. Cek Rating (BARU)
                const matchesRating = selectedRating === 'Semua Umur' || filmRating === selectedRating;

                // Tampilkan jika SEMUA kriteria cocok
                if (matchesSearch && matchesGenre && matchesRating) {
                    card.style.display = 'flex'; 
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update UI
            resultCount.textContent = `Menampilkan ${visibleCount} film`;

            if (visibleCount === 0) {
                noResultsMessage.classList.remove('hidden');
            } else {
                noResultsMessage.classList.add('hidden');
            }
        }

        // Global function untuk tombol reset
        window.resetFilters = function() {
            searchInput.value = '';
            genreSelect.value = 'Semua Genre';
            ratingSelect.value = 'Semua Umur'; // Reset rating juga
            filterMovies();
        };

        // Event Listeners
        searchInput.addEventListener('input', filterMovies);
        genreSelect.addEventListener('change', filterMovies);
        ratingSelect.addEventListener('change', filterMovies); // Listener baru
    });
  </script>

</body>
</html>
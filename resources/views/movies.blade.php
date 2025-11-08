<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FilmPass Clone</title>
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

    .line-clamp-2 {
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
      -webkit-line-clamp: 2;
    }

    #searchInput:focus, #genreSelect:focus {
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
  </style>
</head>

<body class="text-white min-h-screen flex flex-col">

  {{-- HEADER --}}
  @include('partials.header')

  <!-- MAIN -->
  <main class="flex-grow pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-6">
      <!-- Kembali ke Dashboard -->
      <div class="mb-6">
        <a href="/dashboard" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition-colors">
          <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
          Kembali ke Dashboard
        </a>
      </div>

      <!-- Judul & Filter -->
      <section class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 space-y-4 md:space-y-0">
        <h1 class="text-3xl font-bold text-white md:mr-10">Temukan Film Favoritmu</h1>

        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
          <div class="relative w-full sm:w-80">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            <input id="searchInput" type="text" placeholder="Cari film..." class="pl-10 pr-4 py-2 bg-gray-800 border border-gray-700 rounded-lg w-full focus:ring-indigo-500 focus:border-indigo-500" oninput="filterMovies()">
          </div>

          <div class="relative">
            <select id="genreSelect" class="appearance-none block w-full bg-gray-800 border border-gray-700 py-2 pl-4 pr-8 rounded-lg leading-tight focus:outline-none focus:border-indigo-500 cursor-pointer text-gray-300" onchange="filterMovies()">
              <option value="Semua Genre">Semua Genre</option>
              <option value="Action">Action</option>
              <option value="Comedy">Comedy</option>
              <option value="Romance">Romance</option>
              <option value="Horror">Horror</option>
              <option value="Sci-Fi">Sci-Fi</option>
              <option value="Drama">Drama</option>
              <option value="Animation">Animation</option>
              <option value="Fantasy">Fantasy</option>
              <option value="Thriller">Thriller</option>
            </select>
            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
          </div>
        </div>
      </section>

      <!-- Jumlah hasil -->
      <div class="mb-6 text-gray-400 text-sm" id="resultCount">
        Menampilkan semua film
      </div>

      <!-- Grid Film -->
      <section id="movieGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        <!-- Diisi lewat JS -->
      </section>
    </div>
  </main>

  {{-- FOOTER --}}
  @include('partials.footer')

  <!-- SCRIPT -->
  <script>
    const allMovies = [
      { title: "Avengers: Endgame", genre: "Action", duration: "3j 2m", image: "https://image.tmdb.org/t/p/w500/ulzhLuWrPK07P1YkdWQLZnQh1JL.jpg", description: "Para Avengers bersatu menghadapi Thanos demi menyelamatkan alam semesta." },
      { title: "Joker", genre: "Drama", duration: "2j 2m", image: "https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg", description: "Kisah hidup Arthur Fleck yang berubah menjadi Joker." },
      { title: "Spider-Man: No Way Home", genre: "Action", duration: "2j 28m", image: "https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg", description: "Peter Parker menghadapi ancaman multiverse setelah identitasnya terbongkar." },
      { title: "Interstellar", genre: "Sci-Fi", duration: "2j 49m", image: "https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg", description: "Sekelompok astronot menjelajahi ruang-waktu demi menyelamatkan umat manusia." },
      { title: "The Conjuring", genre: "Horror", duration: "1j 52m", image: "https://image.tmdb.org/t/p/w500/wVYREutTvI2tmxr6ujrHT704wGF.jpg", description: "Penyelidikan Ed dan Lorraine Warren terhadap rumah berhantu." },
      { title: "Crazy Rich Asians", genre: "Romance", duration: "2j 1m", image: "https://image.tmdb.org/t/p/w500/1XxL4LJ5WHdrcYcihEZUCgNCpAW.jpg", description: "Cinta diuji ketika Rachel bertemu keluarga super kaya kekasihnya." },
      { title: "Free Guy", genre: "Comedy", duration: "1j 55m", image: "https://image.tmdb.org/t/p/w500/xmbU4JTUm8rsdtn7Y3Fcm30GpeT.jpg", description: "Seorang NPC di video game mulai sadar dan berusaha menjadi pahlawan." },
      { title: "Tenet", genre: "Thriller", duration: "2j 30m", image: "https://image.tmdb.org/t/p/w500/k68nPLbIST6NP96JmTxmZijEvCA.jpg", description: "Agen rahasia berjuang melawan ancaman waktu untuk menyelamatkan dunia." },
      { title: "Frozen II", genre: "Animation", duration: "1j 43m", image: "https://image.tmdb.org/t/p/w500/qdfARIhgpgZOBh3vfNhWS4hmSo3.jpg", description: "Elsa mencari asal kekuatannya bersama Anna, Kristoff, dan Olaf." },
      { title: "The Batman", genre: "Action", duration: "2j 55m", image: "https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg", description: "Batman menghadapi Riddler yang mengungkap korupsi di Gotham." },
      { title: "Black Panther", genre: "Action", duration: "2j 14m", image: "https://image.tmdb.org/t/p/w500/uxzzxijgPIY7slzFvMotPv8wjKA.jpg", description: "T'Challa kembali ke Wakanda untuk menjadi raja dan melindungi negerinya." },
      { title: "The Notebook", genre: "Romance", duration: "2j 3m", image: "https://image.tmdb.org/t/p/w500/rNzQyW4f8B8cQeg7Dgj3n6eT5k9.jpg", description: "Kisah cinta sejati yang bertahan melampaui waktu dan kenangan." },
      { title: "Parasite", genre: "Drama", duration: "2j 12m", image: "https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg", description: "Keluarga miskin menyusup ke rumah orang kaya dengan konsekuensi mematikan." },
      { title: "Inception", genre: "Sci-Fi", duration: "2j 28m", image: "https://image.tmdb.org/t/p/w500/edv5CZvWj09upOsy2Y6IwDhK8bt.jpg", description: "Pencuri mimpi berusaha menanam ide dalam alam bawah sadar seseorang." },
      { title: "A Quiet Place", genre: "Horror", duration: "1j 30m", image: "https://image.tmdb.org/t/p/w500/nAU74GmpUk7t5iklEp3bufwDq4n.jpg", description: "Keluarga bertahan hidup di dunia yang dikuasai makhluk peka suara." },
      { title: "Your Name", genre: "Animation", duration: "1j 46m", image: "https://image.tmdb.org/t/p/w500/q719jXXEzOoYaps6babgKnONONX.jpg", description: "Dua remaja bertukar tubuh dan mencari satu sama lain lintas waktu." },
      { title: "Doctor Strange", genre: "Fantasy", duration: "1j 55m", image: "https://image.tmdb.org/t/p/w500/uGBVj3bEbCoZbDjjl9wTxcygko1.jpg", description: "Ahli bedah menjadi penyihir pelindung dunia dari ancaman mistis." },
      { title: "Shang-Chi", genre: "Action", duration: "2j 12m", image: "https://image.tmdb.org/t/p/w500/1BIoJGKbXjdFDAqUEiA2VHqkK1Z.jpg", description: "Seorang pemuda menghadapi masa lalunya sebagai pewaris kekuatan legendaris." },
      { title: "The Matrix", genre: "Sci-Fi", duration: "2j 16m", image: "https://upload.wikimedia.org/wikipedia/id/c/c1/The_Matrix_Poster.jpg", description: "Neo menemukan dunia maya palsu dan berjuang untuk kebebasan manusia." },
      { title: "Knives Out", genre: "Thriller", duration: "2j 10m", image: "https://image.tmdb.org/t/p/w500/pThyQovXQrw2m0s9x82twj48Jq4.jpg", description: "Detektif menyelidiki kematian misterius penulis kaya raya." }
    ];

    function displayMovies(movies) {
      const grid = document.getElementById("movieGrid");
      const count = document.getElementById("resultCount");
      grid.innerHTML = "";

      if (movies.length === 0) {
        grid.innerHTML = `<p class='text-gray-400 col-span-full text-center'>Tidak ada film ditemukan.</p>`;
        count.textContent = "Menampilkan 0 film";
        return;
      }

      grid.innerHTML = movies.map(m => `
        <div class="bg-dark-card rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 flex flex-col">
          <img src="${m.image}" alt="Poster Film ${m.title}" class="w-full h-64 object-cover">
          <div class="p-4 flex flex-col flex-grow">
            <h3 class="text-lg font-bold text-white mb-1">${m.title}</h3>
            <p class="text-sm text-gray-400 mb-2">${m.genre} • ${m.duration}</p>
            <p class="text-sm text-gray-300 line-clamp-2 mb-4 flex-grow">${m.description}</p>
            <button class="booking-btn w-full mt-auto bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md flex items-center justify-center space-x-2" data-movie-title="${m.title}">
                <i data-lucide="ticket" class="w-4 h-4"></i>
                <span>Pesan Tiket</span>
            </button>
          </div>
        </div>
      `).join('');

      count.textContent = `Menampilkan ${movies.length} film`;
      lucide.createIcons();
    }

    function filterMovies() {
      const search = document.getElementById("searchInput").value.toLowerCase();
      const genre = document.getElementById("genreSelect").value;

      const filtered = allMovies.filter(m => {
        const byGenre = genre === "Semua Genre" || m.genre === genre;
        const bySearch = m.title.toLowerCase().includes(search) || m.description.toLowerCase().includes(search);
        return byGenre && bySearch;
      });

      displayMovies(filtered);
    }

    document.addEventListener('click', e => {
  const btn = e.target.closest('.booking-btn');
  if (btn) {
    const movieTitle = btn.dataset.movieTitle;
    // Redirect ke halaman pemesanan
    window.location.href = `/pesan-tiket/${encodeURIComponent(movieTitle)}`;
  }
});

    window.onload = () => {
      filterMovies();
      lucide.createIcons();
    };
  </script>
</body>
</html>

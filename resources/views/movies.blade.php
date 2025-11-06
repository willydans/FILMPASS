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
    
    /* Menambahkan transisi ke input dan select untuk efek focus yang lebih halus */
    #searchInput:focus, #genreSelect:focus {
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
  </style>
</head>

<body class="text-white min-h-screen flex flex-col">

  <!-- HEADER -->
  <header class="bg-gray-900 shadow-md fixed top-0 left-0 right-0 z-30">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <div class="flex items-center space-x-3">
        <i data-lucide="clapperboard" class="w-6 h-6 text-indigo-500"></i>
        <span class="text-2xl font-bold"><span class="text-indigo-500">Film</span>Pass</span>
      </div>

      <nav class="hidden md:flex space-x-6 text-gray-300">
        <a href="#" class="hover:text-indigo-400 transition">Beranda</a>
        <a href="#" class="hover:text-indigo-400 transition">Film</a>
        <a href="#" class="hover:text-indigo-400 transition">Jadwal</a>
        <a href="#" class="hover:text-indigo-400 transition">Kontak</a>
      </nav>

      <button class="md:hidden text-gray-300 hover:text-indigo-400">
        <i data-lucide="menu" class="w-6 h-6"></i>
      </button>
    </div>
  </header>

  <!-- MAIN -->
  <main class="flex-grow mt-20 px-6 max-w-7xl mx-auto">
    <section class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 space-y-4 md:space-y-0">
      <h1 class="text-3xl font-bold text-white">Temukan Film Favoritmu</h1>
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

    <div class="mb-6 text-gray-400 text-sm" id="resultCount">
      Menampilkan semua film
    </div>

    <section id="movieGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-16">
      <!-- Film akan diisi oleh JavaScript -->
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-900 text-gray-400 pt-12 pb-10 mt-auto">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
      <div class="md:col-span-2 space-y-4">
        <div class="text-2xl font-extrabold flex items-center">
          <i data-lucide="clapperboard" class="w-6 h-6 mr-2 text-indigo-500"></i>
          <span class="text-white">Film</span><span class="text-indigo-500">Pass</span>
        </div>
        <p class="text-gray-400 text-sm max-w-sm">
          Platform pemesanan tiket bioskop online terpercaya.
        </p>
        <div class="flex space-x-4 text-gray-400">
          <a href="#" class="hover:text-white"><i data-lucide="facebook" class="w-5 h-5"></i></a>
          <a href="#" class="hover:text-white"><i data-lucide="twitter" class="w-5 h-5"></i></a>
          <a href="#" class="hover:text-white"><i data-lucide="instagram" class="w-5 h-5"></i></a>
          <a href="#" class="hover:text-white"><i data-lucide="youtube" class="w-5 h-5"></i></a>
        </div>
      </div>

      <div>
        <h4 class="text-lg font-semibold mb-4 text-white">Tautan Cepat</h4>
        <nav class="space-y-2 text-sm">
          <a href="#" class="block hover:text-indigo-400 transition">Beranda</a>
          <a href="#" class="block hover:text-indigo-400 transition">Film</a>
          <a href="#" class="block hover:text-indigo-400 transition">Cari Film</a>
          <a href="#" class="block hover:text-indigo-400 transition">Tentang Kami</a>
        </nav>
      </div>

      <div>
        <h4 class="text-lg font-semibold mb-4 text-white">Bantuan</h4>
        <nav class="space-y-2 text-sm">
          <a href="#" class="block hover:text-indigo-400 transition">Pusat Bantuan</a>
          <a href="#" class="block hover:text-indigo-400 transition">Hubungi Kami</a>
          <a href="#" class="block hover:text-indigo-400 transition">Syarat & Ketentuan</a>
          <a href="#" class="block hover:text-indigo-400 transition">Kebijakan Privasi</a>
        </nav>
      </div>
    </div>
    <div class="max-w-7xl mx-auto px-6 mt-10 border-t border-gray-700 pt-6">
      <p class="text-center text-sm text-gray-500">&copy; 2025 FilmPass Clone. All rights reserved.</p>
    </div>
  </footer>
  
  <!-- Elemen Notifikasi (Toast) -->
  <div id="notification" class="fixed bottom-5 right-5 p-3 bg-indigo-600 text-white rounded-lg shadow-2xl z-50 transition duration-300 ease-in-out opacity-0 translate-y-full pointer-events-none">
    Pemesanan tiket disimulasikan!
  </div>

  <!-- SCRIPT -->
  <script>
    
    // Data Film
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

    /**
     * Menampilkan pesan simulasi pemesanan tiket sebagai notifikasi toast.
     * @param {string} title Judul film yang akan dipesan.
     */
    function showBookingMessage(title) {
        const notification = document.getElementById('notification');
        notification.textContent = `Berhasil memilih film "${title}"! (Simulasi Pemesanan)`;
        
        // Menampilkan notifikasi (menggeser ke atas dan mengatur opasitas)
        notification.classList.remove('opacity-0', 'translate-y-full', 'pointer-events-none');
        notification.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');

        // Menyembunyikan notifikasi setelah 3 detik
        setTimeout(() => {
            notification.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            notification.classList.add('opacity-0', 'translate-y-full', 'pointer-events-none');
        }, 3000);
    }

    /**
     * Merender daftar film ke dalam grid.
     * @param {Array<Object>} movies Daftar film yang akan ditampilkan.
     */
    function displayMovies(movies) {
      const grid = document.getElementById("movieGrid");
      const count = document.getElementById("resultCount");
      grid.innerHTML = "";

      if (movies.length === 0) {
        grid.innerHTML = `<p class='text-gray-400 col-span-full text-center'>Tidak ada film ditemukan.</p>`;
        count.textContent = "Menampilkan 0 film";
        return;
      }

      const movieHtml = movies.map(m => `
        <div class="bg-dark-card rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 flex flex-col">
          <img src="${m.image}" alt="Poster Film ${m.title}" class="w-full h-64 object-cover">
          <div class="p-4 flex flex-col flex-grow">
            <h3 class="text-lg font-bold text-white mb-1">${m.title}</h3>
            <p class="text-sm text-gray-400 mb-2">${m.genre} • ${m.duration}</p>
            <p class="text-sm text-gray-300 line-clamp-2 mb-4 flex-grow">${m.description}</p>
            <!-- Tombol Pemesanan - menggunakan class 'booking-btn' untuk delegasi event -->
            <button class="booking-btn w-full mt-auto bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition duration-150 shadow-md flex items-center justify-center space-x-2" data-movie-title="${m.title}">
                <i data-lucide="ticket" class="w-4 h-4"></i>
                <span>Pesan Tiket</span>
            </button>
          </div>
        </div>
      `).join('');

      grid.innerHTML = movieHtml;

      count.textContent = `Menampilkan ${movies.length} film`;
      // Panggil kembali lucide.createIcons() untuk ikon di dalam tombol
      lucide.createIcons();
    }

    /**
     * Menyaring film berdasarkan input pencarian dan genre yang dipilih.
     */
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
    
    // --- EVENT DELEGATION FOR BUTTONS ---
    // Menggunakan delegasi event pada parent container (movieGrid)
    document.addEventListener('click', (event) => {
        // Mencari elemen tombol terdekat dengan class 'booking-btn'
        const button = event.target.closest('.booking-btn');
        
        if (button) {
            const title = button.getAttribute('data-movie-title');
            showBookingMessage(title);
        }
    });

    // Inisialisasi pada saat dokumen selesai dimuat
    window.onload = () => {
        filterMovies(); // Memuat film di awal
        lucide.createIcons(); // Memuat semua ikon setelah DOM siap
    };
  </script>
</body>
</html>


@section('title', 'Manajemen Film')

<div class="max-w-7xl mx-auto">
    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen Film</h1>
            <p class="text-gray-600">Kelola koleksi film bioskop dengan mudah</p>
        </div>
        <a href="{{ route('admin.films.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg inline-flex items-center shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Film
        </a>
    </div>

    {{-- FILTER & SEARCH --}}
    <div class="mb-8 p-6 bg-white rounded-xl shadow-md border border-gray-100">
        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 items-center">
            <div class="relative flex-1 w-full">
                <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="search" 
                    id="searchInput" 
                    placeholder="Cari film..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-blue-500 focus:outline-none"
                    onkeyup="filterMovies()">
            </div>

            <div class="w-full sm:w-auto sm:min-w-[150px]">
                <select 
                    id="genreSelect"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm bg-white focus:ring-1 focus:ring-blue-500 focus:outline-none"
                    onchange="filterMovies()">
                </select>
            </div>
        </div>
    </div>

    {{-- DAFTAR FILM --}}
    <div id="moviesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const allMovies = [
        { id: 1, title: "Avengers: Endgame", genre: "Action", duration: "3j 2m", image: "https://image.tmdb.org/t/p/w500/ulzhLuWrPK07P1YkdWQLZnQh1JL.jpg", description: "Para Avengers bersatu menghadapi Thanos demi menyelamatkan alam semesta.", status: 'active' },
        { id: 2, title: "Joker", genre: "Drama", duration: "2j 2m", image: "https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg", description: "Kisah hidup Arthur Fleck yang berubah menjadi Joker.", status: 'active' },
        { id: 3, title: "Interstellar", genre: "Sci-Fi", duration: "2j 49m", image: "https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg", description: "Astronot menjelajahi ruang-waktu demi menyelamatkan umat manusia.", status: 'active' },
        { id: 4, title: "Frozen II", genre: "Animation", duration: "1j 43m", image: "https://image.tmdb.org/t/p/w500/qdfARIhgpgZOBh3vfNhWS4hmSo3.jpg", description: "Elsa mencari asal kekuatannya bersama Anna, Kristoff, dan Olaf.", status: 'inactive' },
        { id: 5, title: "Parasite", genre: "Drama", duration: "2j 12m", image: "https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg", description: "Keluarga miskin menyusup ke rumah orang kaya dengan konsekuensi mematikan.", status: 'active' },
        { id: 6, title: "Your Name", genre: "Animation", duration: "1j 46m", image: "https://image.tmdb.org/t/p/w500/q719jXXEzOoYaps6babgKnONONX.jpg", description: "Dua remaja bertukar tubuh dan mencari satu sama lain lintas waktu.", status: 'inactive' }
    ];

    function createMovieCard(movie) {
        const statusBadge = movie.status === 'active'
            ? '<span class="absolute top-3 right-3 text-xs font-bold px-3 py-1 bg-green-500 text-white rounded-full shadow-md">AKTIF</span>'
            : '<span class="absolute top-3 right-3 text-xs font-bold px-3 py-1 bg-red-500 text-white rounded-full shadow-md">NONAKTIF</span>';

        return `
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200 hover:shadow-2xl transition duration-300">
            <div class="relative w-full h-72 bg-gray-200">
                <img src="${movie.image}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x500/cccccc/333333?text=No+Poster';">
                ${statusBadge}
            </div>
            <div class="p-4">
                <h2 class="text-xl font-bold text-gray-900 mb-1 line-clamp-1">${movie.title}</h2>
                <p class="text-sm text-gray-500 mb-2">${movie.genre}</p>
                <div class="flex justify-between items-center text-xs text-gray-600 mb-3">
                    <span>${movie.duration}</span>
                    <span class="font-medium px-2 py-0.5 bg-gray-200 rounded">${movie.status.toUpperCase()}</span>
                </div>
                <p class="text-sm text-gray-600 line-clamp-2 mb-3">${movie.description}</p>
                <div class="flex justify-between mt-3 border-t pt-3">
                    <a href="/admin/films/${movie.id}/edit" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition">Edit</a>
                    <button class="text-sm text-red-600 hover:text-red-800 font-medium transition" onclick="toggleStatus(${movie.id})">${movie.status === 'active' ? 'Nonaktif' : 'Aktifkan'}</button>
                </div>
            </div>
        </div>`;
    }

    function renderMovies(moviesToRender) {
        const grid = document.getElementById('moviesGrid');
        grid.innerHTML = moviesToRender.map(createMovieCard).join('');
    }

    function populateGenres() {
        const genreSelect = document.getElementById('genreSelect');
        const genres = new Set(allMovies.map(movie => movie.genre));
        let optionsHtml = '<option value="">Semua Genre</option>';
        genres.forEach(genre => {
            optionsHtml += `<option value="${genre}">${genre}</option>`;
        });
        genreSelect.innerHTML = optionsHtml;
    }

    function filterMovies() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const selectedGenre = document.getElementById('genreSelect').value;
        const filtered = allMovies.filter(movie => {
            const matchTitle = movie.title.toLowerCase().includes(search);
            const matchDesc = movie.description.toLowerCase().includes(search);
            const matchGenre = selectedGenre === "" || movie.genre === selectedGenre;
            return (matchTitle || matchDesc) && matchGenre;
        });
        renderMovies(filtered);
    }

    populateGenres();
    filterMovies();
});
</script>

@extends('layouts.admin')


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;

class UserDashboardController extends Controller
{
    /**
     * Menampilkan halaman beranda user (Dashboard)
     * dengan data film dari database, fitur search, dan pagination.
     */
    public function index(Request $request)
    {
        // 1. Ambil query pencarian dari URL (jika ada)
        $search = $request->input('search');

        // 2. Ambil 3 Film Terbaru untuk Carousel (Featured)
        // Film terbaru ditampilkan di slider atas
        $featuredFilms = Film::latest()->take(3)->get();

        // 3. Siapkan Query untuk 'Film Populer' (List Bawah)
        $query = Film::query();

        // Jika ada pencarian, filter berdasarkan judul, genre, atau deskripsi
        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('genre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // 4. Ambil data dengan Pagination (12 film per halaman)
        // Menggunakan paginate() agar halaman tidak berat memuat semua film sekaligus
        $popularFilms = $query->orderBy('created_at', 'desc')->paginate(12);

        // 5. (Opsional) Data Genre Statis untuk navigasi cepat
        // Ini dikirim jika Anda ingin menggunakannya di view, meski view sebelumnya hardcode icon
        $genres = [
            ['name' => 'Action', 'icon' => '💥'],
            ['name' => 'Drama', 'icon' => '🎭'],
            ['name' => 'Sci-Fi', 'icon' => '🚀'],
            ['name' => 'Romance', 'icon' => '❤️'],
            ['name' => 'Comedy', 'icon' => '😂'],
            ['name' => 'Horror', 'icon' => '👻'],
            ['name' => 'Thriller', 'icon' => '🔪'],
            ['name' => 'Animation', 'icon' => '🎨']
        ];

        return view('dashboard', [
            'featuredFilms' => $featuredFilms,
            'popularFilms' => $popularFilms,
            'genres' => $genres,
            'search' => $search // Penting dikirim balik untuk input value di view
        ]);
    }
}
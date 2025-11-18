<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;

class UserDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil 3 Film Terbaru untuk Carousel (Featured)
        // Anda bisa menambahkan kolom 'is_featured' di database nanti jika mau lebih spesifik
        $featuredFilms = Film::latest()->take(3)->get();

        // 2. Ambil 6 Film untuk bagian 'Film Populer'
        // (Bisa diurutkan berdasarkan rating atau random)
        $popularFilms = Film::inRandomOrder()->take(6)->get();

        // 3. Ambil Semua Genre Unik untuk filter
        // Asumsi kolom 'rating' atau 'genre' (jika ada) digunakan untuk ini
        // Jika belum ada kolom genre, kita bisa hardcode dulu atau pakai rating
        $genres = ['Action', 'Drama', 'Sci-Fi', 'Romance', 'Comedy', 'Thriller']; 

        return view('dashboard', [
            'featuredFilms' => $featuredFilms,
            'popularFilms' => $popularFilms,
            'genres' => $genres
        ]);
    }
}
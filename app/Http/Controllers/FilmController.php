<?php

namespace App\Http\Controllers;

use App\Models\Film; // <-- Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Pastikan ini ada

class FilmController extends Controller
{
    /**
     * Menyimpan film baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi request (pastikan ada file gambar, dll.)
        $request->validate([
            'title' => 'required|string|max:255',
            'poster_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // 'poster_file' adalah nama input di form Anda
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer',
            'release_date' => 'nullable|date',
            'rating' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('poster_file')) {
            // 1. Ambil file dari request
            $file = $request->file('poster_file');
            
            // 2. Simpan file ke 'storage/app/public/posters'
            // 'public' adalah disk yang kita link
            $path = $file->store('posters', 'public');
        }

        // 3. Simpan data ke database
        $film = new Film;
        $film->title = $request->title;
        $film->description = $request->description;
        $film->poster_path = $path; // <-- Simpan PATH-nya
        $film->duration_minutes = $request->duration_minutes;
        $film->release_date = $request->release_date;
        $film->rating = $request->rating;
        $film->save();

        // Arahkan kembali ke halaman daftar film (buat rute ini nanti)
        // Kita beri nama 'admin.films.index' sebagai contoh
        return redirect()->route('admin.films.index')->with('success', 'Film berhasil ditambahkan!');
    }

    // Nanti Anda juga akan butuh fungsi lain di sini:
    // public function index() { ... }     // Untuk menampilkan semua film
    // public function create() { ... }    // Untuk menampilkan form tambah film
    // public function edit(Film $film) { ... }  // Untuk menampilkan form edit
    // public function update(Request $request, Film $film) { ... } // Untuk menyimpan editan
    // public function destroy(Film $film) { ... } // Untuk menghapus film
}
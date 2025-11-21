<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    /**
     * Menampilkan daftar semua film
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $films = Film::when($search, function($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%")
                         ->orWhere('genre', 'like', "%{$search}%") // Tambahan search by genre
                         ->orWhere('rating', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('admin.films.index', compact('films', 'search'));
    }

    /**
     * Menampilkan form tambah film
     */
    public function create()
    {
        return view('admin.films.create');
    }

    /**
     * Menyimpan film baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'poster_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'genre' => 'required|array', // Ubah jadi array
            'genre.*' => 'string',       // Pastikan isinya string
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'nullable|date',
            'rating' => 'required|string|max:10',
        ]);

        // 2. Upload File
        $path = null;
        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $path = $file->store('posters', 'public');
        }

        // 3. Simpan Data
        Film::create([
            'title' => $request->title,
            'description' => $request->description,
            // Ubah Array Genre menjadi String dipisah koma (ex: "Action, Horror")
            'genre' => implode(', ', $request->genre), 
            'poster_path' => $path, // Sesuaikan nama kolom di DB (poster_path atau poster_url)
            'duration_minutes' => $request->duration_minutes,
            'release_date' => $request->release_date,
            'rating' => $request->rating,
        ]);

        return redirect()->route('admin.films.index')->with('success', 'Film berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit film
     */
    public function edit(Film $film)
    {
        return view('admin.films.edit', compact('film'));
    }

    /**
     * Update film di database
     */
    public function update(Request $request, Film $film)
    {
        // 1. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'genre' => 'required|array', // Ubah jadi array
            'genre.*' => 'string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'nullable|date',
            'rating' => 'required|string|max:10',
        ]);

        // 2. Siapkan Data Update
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            // Ubah Array Genre menjadi String lagi saat update
            'genre' => implode(', ', $request->genre),
            'duration_minutes' => $request->duration_minutes,
            'release_date' => $request->release_date,
            'rating' => $request->rating,
        ];

        // 3. Cek Upload Poster Baru
        if ($request->hasFile('poster_file')) {
            // Hapus poster lama jika ada
            if ($film->poster_path) {
                Storage::disk('public')->delete($film->poster_path);
            }
            // Upload poster baru
            $file = $request->file('poster_file');
            $data['poster_path'] = $file->store('posters', 'public');
        }

        // 4. Update Database
        $film->update($data);

        return redirect()->route('admin.films.index')->with('success', 'Film berhasil diperbarui!');
    }

    /**
     * Hapus film dari database
     */
    public function destroy(Film $film)
    {
        // Hapus file poster dari storage
        if ($film->poster_path) {
            Storage::disk('public')->delete($film->poster_path);
        }

        $film->delete();

        return redirect()->route('admin.films.index')->with('success', 'Film berhasil dihapus!');
    }
}
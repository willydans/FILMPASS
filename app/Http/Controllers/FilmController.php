<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'title' => 'required|string|max:255',
            'poster_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'nullable|date',
            'rating' => 'nullable|string|max:10',
        ]);

        $path = null;
        if ($request->hasFile('poster_file')) {
            $file = $request->file('poster_file');
            $path = $file->store('posters', 'public');
        }

        Film::create([
            'title' => $request->title,
            'description' => $request->description,
            'poster_path' => $path,
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
        $request->validate([
            'title' => 'required|string|max:255',
            'poster_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'nullable|date',
            'rating' => 'nullable|string|max:10',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'release_date' => $request->release_date,
            'rating' => $request->rating,
        ];

        // Jika ada upload poster baru
        if ($request->hasFile('poster_file')) {
            // Hapus poster lama
            if ($film->poster_path) {
                Storage::disk('public')->delete($film->poster_path);
            }
            // Upload poster baru
            $file = $request->file('poster_file');
            $data['poster_path'] = $file->store('posters', 'public');
        }

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
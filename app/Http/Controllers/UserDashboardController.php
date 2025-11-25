<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserDashboardController extends Controller
{
    /**
     * Menampilkan halaman beranda user (Dashboard)
     * dengan data film dari database, fitur search, dan pagination.
     */
    public function index(Request $request)
    {
        // Validasi input search
        $request->validate([
            'search' => 'nullable|string|max:100'
        ]);

        // Ambil query pencarian dari URL dan ubah ke lowercase
        $search = $request->input('search');
        $searchTerm = $search ? Str::lower(trim($search)) : null;

        // Featured Films (3 terbaru) - dengan cache
        $featuredFilms = Cache::remember('featured_films', 3600, function() {
            return Film::latest()->take(3)->get();
        });

        // Query untuk Popular Films
        $query = Film::query();

        // PERBAIKAN: Filter search dengan LOWER() untuk case-insensitive
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(genre) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Pagination dengan append search parameter
        $popularFilms = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends(['search' => $search]); // Tetap kirim original search (bukan lowercase)

        // Genre list
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

        return view('dashboard', compact(
            'featuredFilms',
            'popularFilms',
            'genres',
            'search'
        ));
    }
}
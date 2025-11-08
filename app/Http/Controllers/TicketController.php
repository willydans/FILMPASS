<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Data film (nantinya bisa dari database)
    private $movies = [
        "Avengers: Endgame" => [
            "title" => "Avengers: Endgame",
            "genre" => "Action",
            "duration" => "3j 2m",
            "image" => "https://image.tmdb.org/t/p/w500/ulzhLuWrPK07P1YkdWQLZnQh1JL.jpg",
            "description" => "Para Avengers bersatu menghadapi Thanos demi menyelamatkan alam semesta.",
            "price" => 50000
        ],
        "Joker" => [
            "title" => "Joker",
            "genre" => "Drama",
            "duration" => "2j 2m",
            "image" => "https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg",
            "description" => "Kisah hidup Arthur Fleck yang berubah menjadi Joker.",
            "price" => 45000
        ],
        "Spider-Man: No Way Home" => [
            "title" => "Spider-Man: No Way Home",
            "genre" => "Action",
            "duration" => "2j 28m",
            "image" => "https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg",
            "description" => "Peter Parker menghadapi ancaman multiverse setelah identitasnya terbongkar.",
            "price" => 50000
        ],
        "Interstellar" => [
            "title" => "Interstellar",
            "genre" => "Sci-Fi",
            "duration" => "2j 49m",
            "image" => "https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg",
            "description" => "Sekelompok astronot menjelajahi ruang-waktu demi menyelamatkan umat manusia.",
            "price" => 55000
        ],
        "The Conjuring" => [
            "title" => "The Conjuring",
            "genre" => "Horror",
            "duration" => "1j 52m",
            "image" => "https://image.tmdb.org/t/p/w500/wVYREutTvI2tmxr6ujrHT704wGF.jpg",
            "description" => "Penyelidikan Ed dan Lorraine Warren terhadap rumah berhantu.",
            "price" => 40000
        ]
        // Tambahkan film lainnya sesuai kebutuhan
    ];

    public function show($title)
    {
        // Decode URL dan cari film
        $movieTitle = urldecode($title);
        
        if (!isset($this->movies[$movieTitle])) {
            abort(404, 'Film tidak ditemukan');
        }
        
        $movie = $this->movies[$movieTitle];
        
        // Data jadwal (contoh)
        $schedules = [
            '2025-11-08' => ['10:00', '13:00', '16:00', '19:00', '21:30'],
            '2025-11-09' => ['10:00', '13:00', '16:00', '19:00', '21:30'],
            '2025-11-10' => ['10:00', '13:00', '16:00', '19:00', '21:30']
        ];
        
        // Cinema locations
        $cinemas = [
            'XXI Bandar Lampung Central',
            'CGV Grand Mall',
            'Cinepolis Kartini',
            'XXI Boemi Kedaton'
        ];
        
        return view('pesan-tiket', compact('movie', 'schedules', 'cinemas'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_title' => 'required|string',
            'cinema' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'seats' => 'required|integer|min:1|max:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string'
        ]);
        
        // Simpan ke database atau session
        // Untuk sementara redirect dengan pesan sukses
        
        return redirect()->back()->with('success', 'Pemesanan tiket berhasil! Kode booking Anda: ' . strtoupper(substr(md5(time()), 0, 8)));
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // PENTING: Untuk akses file storage

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'genre',
        'poster_path',
        'trailer_url', // <-- Pastikan ini ada untuk fitur trailer
        'duration_minutes',
        'release_date',
        'rating',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    /**
     * Relasi ke Jadwal (One to Many)
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Accessor: URL Poster Otomatis ($film->poster_url)
     * Mengubah path 'posters/file.jpg' menjadi URL lengkap
     */
    public function getPosterUrlAttribute()
    {
        if ($this->poster_path) {
            return Storage::url($this->poster_path);
        }

        // Placeholder jika tidak ada gambar
        return 'https://placehold.co/300x450/2A2A2A/FFF?text=' . urlencode($this->title);
    }

    /**
     * Accessor: URL Embed YouTube ($film->trailer_embed_url)
     * Mengubah link YouTube biasa menjadi format embed iframe
     */
    public function getTrailerEmbedUrlAttribute()
    {
        if (!$this->trailer_url) return null;

        $url = $this->trailer_url;
        
        // Handle short URL (youtu.be/ID)
        $url = preg_replace('/youtu\.be\/(.*)/', 'youtube.com/watch?v=$1', $url);
        
        // Handle standard URL (watch?v=ID) -> embed/ID
        return preg_replace('/watch\?v=([^&]*).*/', 'embed/$1', $url);
    }
}
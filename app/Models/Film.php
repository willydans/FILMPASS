<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <-- PENTING: Tambahkan ini

class Film extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Ini adalah kolom-kolom dari form 'Tambah Film' Anda.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'genre',
        'poster_path',      // Path file yang disimpan dari upload
        'duration_minutes',
        'release_date',
        'rating',
    ];

    /**
     * Tipe data bawaan (casts) untuk atribut.
     * Ini akan otomatis mengubah string 'release_date' dari database
     * menjadi objek Carbon (objek tanggal) yang bisa Anda format.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date',
    ];

    /**
     * RELASI: Mendapatkan semua jadwal tayang untuk film ini.
     *
     * Ini adalah relasi one-to-many:
     * Satu film (Film) memiliki banyak (hasMany) jadwal (Schedules).
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * ACCESOR (Atribut Tambahan):
     * Membuat atribut virtual 'poster_url' secara otomatis.
     * * Saat Anda memanggil $film->poster_url di Controller atau View,
     * fungsi ini akan otomatis dijalankan.
     *
     * @return string
     */
    public function getPosterUrlAttribute()
    {
        // Cek apakah 'poster_path' (kolom di DB) ada isinya dan tidak null
        if ($this->poster_path) {
            
            // Mengembalikan URL publik yang bisa diakses
            // (Hasil dari 'php artisan storage:link')
            return Storage::url($this->poster_path);
        }

        // Jika tidak ada poster, kembalikan gambar placeholder
        // Ini akan membuat placeholder seperti "https://.../text=Spider-Man"
        return 'https://placehold.co/300x450/2A2A2A/FFF?text=' . urlencode($this->title);
    }
}
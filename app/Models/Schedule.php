<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'film_id',
        'studio_id',
        'start_time',
        'end_time',
        'price',
        'status', // <--- PERBAIKAN: Kolom status ditambahkan agar bisa di-update
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relasi
    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
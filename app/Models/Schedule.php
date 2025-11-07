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
        'status',
    ];

    /**
     * Tipe data bawaan (casts) untuk atribut.
     * Ini akan otomatis mengubah 'start_time' dan 'end_time'
     * menjadi objek Carbon (objek tanggal).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'integer',
    ];

    /**
     * RELASI: Mendapatkan film yang terkait dengan jadwal ini.
     * (Satu Jadwal dimiliki oleh satu Film)
     */
    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * RELASI: Mendapatkan studio yang terkait dengan jadwal ini.
     * (Satu Jadwal dimiliki oleh satu Studio)
     */
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * RELASI: Mendapatkan semua pemesanan untuk jadwal ini.
     * (Satu Jadwal memiliki banyak Pemesanan)
     */
    public function bookings()
    {
        // Kita hanya peduli pemesanan yang 'confirmed'
        // untuk perhitungan okupansi
        return $this->hasMany(Booking::class)->where('booking_status', 'confirmed');
    }
}
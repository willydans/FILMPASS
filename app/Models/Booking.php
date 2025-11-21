<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'schedule_id',
        'user_id',
        'seat_count',
        'seats',           // <--- Tambahan: Menyimpan daftar kursi (A1, A2)
        'total_price',
        'payment_method',  // <--- Tambahan: Metode pembayaran
        'payment_status',  // <--- Tambahan: Status pembayaran
        'booking_status',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'seat_count' => 'integer',
        'total_price' => 'integer',
    ];

    /**
     * Relasi ke Jadwal (Schedule)
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Relasi ke User (Pelanggan)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
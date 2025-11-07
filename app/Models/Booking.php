<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'schedule_id',
        'user_id',
        'seat_count',
        'total_price',
        'booking_status',
    ];

    /**
     * Tipe data bawaan (casts) untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'seat_count' => 'integer',
        'total_price' => 'integer', // Sesuai migrasi Anda
    ];

    /**
     * RELASI: Mendapatkan jadwal tayang (schedule) yang terkait dengan pemesanan ini.
     *
     * Ini adalah relasi many-to-one:
     * Satu pemesanan (Booking) dimiliki oleh (belongsTo) satu jadwal (Schedule).
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * RELASI: Mendapatkan pengguna (user) yang terkait dengan pemesanan ini.
     *
     * Ini adalah relasi many-to-one:
     * Satu pemesanan (Booking) dimiliki oleh (belongsTo) satu pengguna (User).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
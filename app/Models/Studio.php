<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule; // <-- TAMBAHKAN INI
use App\Models\Booking;  // <-- TAMBAHKAN INI

class Studio extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'name',
        'type',
        'capacity',
        'status', 
        'base_price', 
        'description',
    ];

    /**
     * Tipe data bawaan (casts) untuk atribut.
     */
    protected $casts = [
        'capacity' => 'integer',
        'base_price' => 'integer',
    ];

    /**
     * RELASI: Mendapatkan semua jadwal tayang untuk studio ini.
     * (Relasi One-to-Many)
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * RELASI: Mendapatkan semua fasilitas yang dimiliki studio ini.
     * (Relasi Many-to-Many)
     */
    public function facilities()
    {
        // (Model relasi, nama pivot table, foreign key model ini, foreign key model relasi)
        return $this->belongsToMany(Facility::class, 'facility_studio', 'studio_id', 'facility_id');
    }

    /**
     * RELASI BARU (PENTING UNTUK OKUPANSI):
     * Mendapatkan semua booking untuk studio ini MELALUI jadwal (schedules).
     */
    public function bookings()
    {
        // (Model tujuan, Model perantara)
        return $this->hasManyThrough(Booking::class, Schedule::class)
                    ->where('bookings.booking_status', 'confirmed'); // Hanya hitung yang sudah dikonfirmasi
    }
}
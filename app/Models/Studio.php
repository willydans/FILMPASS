<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'capacity',
        'status',       // <-- DITAMBAHKAN
        'base_price',   // <-- DITAMBAHKAN
        'description',  // <-- DITAMBAHKAN
    ];

    /**
     * Tipe data bawaan (casts) untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'capacity' => 'integer',
        'base_price' => 'integer', // <-- DITAMBAHKAN
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
}
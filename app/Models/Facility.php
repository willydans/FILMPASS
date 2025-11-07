<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Nama fasilitas, misal: "Dolby Atmos"
    ];

    /**
     * RELASI: Mendapatkan semua studio yang memiliki fasilitas ini.
     *
     * Ini adalah relasi Many-to-Many:
     * Satu fasilitas (Facility) bisa dimiliki oleh banyak (belongsToMany) studio (Studios).
     */
    public function studios()
    {
        // Kita definisikan nama tabel pivotnya secara eksplisit
        // (Model relasi, nama pivot table, foreign key model ini, foreign key model relasi)
        return $this->belongsToMany(Studio::class, 'facility_studio', 'facility_id', 'studio_id');
    }
}
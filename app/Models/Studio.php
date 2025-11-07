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
    ];

    /**
     * Tipe data bawaan (casts) untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'capacity' => 'integer',
    ];

    /**
     * RELASI: Mendapatkan semua jadwal tayang untuk studio ini.
     *
     * Ini adalah relasi one-to-many:
     * Satu studio (Studio) memiliki banyak (hasMany) jadwal (Schedules).
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
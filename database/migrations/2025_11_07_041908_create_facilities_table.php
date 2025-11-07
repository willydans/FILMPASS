<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini akan menyimpan nama-nama fasilitas
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Dolby Atmos", "Reclining Seats"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
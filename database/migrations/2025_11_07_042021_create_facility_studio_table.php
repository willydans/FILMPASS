<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ini adalah tabel PIVOT yang menghubungkan studios dan facilities
        Schema::create('facility_studio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            
            // Opsional: pastikan satu fasilitas tidak bisa ditambah 2x ke studio yang sama
            $table->unique(['facility_id', 'studio_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_studio');
    }
};
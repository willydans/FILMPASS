<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->comment('IMAX, VIP, Regular');
            $table->integer('capacity');
            
            // --- KOLOM YANG HILANG (TAMBAHKAN INI) ---

            // 1. Untuk 'Status' (Contoh: "Aktif", "Maintenance")
            $table->string('status')->default('Aktif'); 

            // 2. Untuk 'Harga Tiket' (Contoh: "Rp 75.000")
            // Kita beri nama 'base_price' agar tidak bentrok dengan harga di jadwal
            $table->integer('base_price')->default(50000); 
            
            // 3. Untuk 'Deskripsi' (Contoh: "Studio IMAX dengan layar...")
            $table->text('description')->nullable(); 

            // ------------------------------------------

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke jadwal dan user
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Detail Kursi
            $table->integer('seat_count'); // Jumlah tiket
            $table->text('seats'); // Menyimpan daftar kursi (contoh: "A1, A2, B5")
            
            // Pembayaran
            $table->bigInteger('total_price');
            $table->string('payment_method')->nullable(); // e.g., "qris", "bank_transfer"
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed
            
            // Status Booking
            $table->string('booking_status')->default('pending'); // pending, confirmed, cancelled
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
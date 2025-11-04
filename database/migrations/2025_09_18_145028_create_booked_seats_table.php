<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booked_seats', function (Blueprint $table) {
            $table->id(); // id (Primary Key)
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade'); // booking_id (Foreign Key)
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade'); // seat_id (Foreign Key)
            $table->foreignId('showtime_id')->constrained('showtimes')->onDelete('cascade'); // showtime_id (Foreign Key)
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booked_seats');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // id (Primary Key)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // user_id (Foreign Key)
            $table->foreignId('showtime_id')->constrained('showtimes')->onDelete('cascade'); // showtime_id (Foreign Key)
            $table->foreignId('cashier_id')->nullable()->constrained('users')->onDelete('set null'); // cashier_id (Foreign Key, nullable)
            $table->timestamp('booking_time')->useCurrent(); // booking_time
            $table->decimal('total_price', 10, 2); // total_price
            $table->enum('status', ['confirmed', 'cancelled']); // status (Enum)
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
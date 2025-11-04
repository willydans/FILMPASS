<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id(); // id (Primary Key)
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade'); // movie_id (Foreign Key)
            $table->foreignId('cinema_hall_id')->constrained('cinema_halls')->onDelete('cascade'); // cinema_hall_id (Foreign Key)
            $table->dateTime('start_time'); // start_time
            $table->dateTime('end_time'); // end_time
            $table->decimal('price', 8, 2); // price (decimal lebih cocok untuk uang)
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
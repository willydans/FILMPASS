<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id(); // id (Primary Key)
            $table->foreignId('cinema_hall_id')->constrained('cinema_halls')->onDelete('cascade'); // cinema_hall_id (Foreign Key)
            $table->string('seat_number'); // seat_number
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cinema_halls', function (Blueprint $table) {
            $table->id(); // id (Primary Key)
            $table->string('name'); // name
            $table->integer('total_seats'); // total_seats
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cinema_halls');
    }
};
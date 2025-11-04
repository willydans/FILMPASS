<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id(); // id (Primary Key)
            $table->string('title'); // title
            $table->text('description'); // description
            $table->date('release_date'); // release_date
            $table->integer('duration_minutes'); // duration_minutes
            $table->string('genre'); // genre
            $table->string('poster_image')->nullable(); // poster_image (nullable jika tidak wajib)
            $table->string('rating'); // rating
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
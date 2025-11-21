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
        Schema::table('films', function (Blueprint $table) {
            // Menambahkan kolom 'genre' setelah kolom 'description'
            // Kita buat nullable dulu untuk jaga-jaga jika ada data lama
            $table->string('genre')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropColumn('genre');
        });
    }
};
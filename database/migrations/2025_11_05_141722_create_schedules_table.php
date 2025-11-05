<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // foreignId() adalah cara modern Laravel untuk foreign key
            $table->foreignId('film_id')->constrained('films')->onDelete('cascade');
            $table->foreignId('studio_id')->constrained('studios')->onDelete('restrict');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('price');
            $table->string('status')->default('terjadwal'); // terjadwal, sedang_tayang, selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
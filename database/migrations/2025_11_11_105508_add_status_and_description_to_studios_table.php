<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            if (!Schema::hasColumn('studios', 'status')) {
                $table->string('status')->default('Aktif')->after('base_price');
            }
            if (!Schema::hasColumn('studios', 'description')) {
                $table->text('description')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn(['status', 'description']);
        });
    }
};

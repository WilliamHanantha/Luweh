<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daftar_makanans', function (Blueprint $table) {
            $table->foreignId('restoran_id')
                ->nullable()
                ->after('id')
                ->constrained('restorans')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('daftar_makanans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('restoran_id');
        });
    }
};

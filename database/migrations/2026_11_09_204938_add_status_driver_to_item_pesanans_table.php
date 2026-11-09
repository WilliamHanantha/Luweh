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
        Schema::table('item_pesanans', function (Blueprint $table) {
            // 'belum_diambil_driver' | 'sudah_diambil_driver'
            $table->string('status_driver')->default('belum_diambil_driver')->after('kuantitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_pesanans', function (Blueprint $table) {
            $table->dropColumn('status_driver');
        });
    }
};

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
        Schema::table('daftar_makanans', function (Blueprint $table) {
            // Path relatif dari public/, misal: assets/images/nasi-goreng.png
            $table->string('gambar')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_makanans', function (Blueprint $table) {
            $table->dropColumn('gambar');
        });
    }
};

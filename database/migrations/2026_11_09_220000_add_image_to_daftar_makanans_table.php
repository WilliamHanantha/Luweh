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
        if (Schema::hasTable('daftar_makanans') && !Schema::hasColumn('daftar_makanans', 'image')) {
            Schema::table('daftar_makanans', function (Blueprint $table) {
                $table->string('image')->nullable()->after('nama_makanan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('daftar_makanans') && Schema::hasColumn('daftar_makanans', 'image')) {
            Schema::table('daftar_makanans', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};

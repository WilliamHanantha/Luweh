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
        Schema::table('pesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanans', 'nama_penerima')) {
                $table->string('nama_penerima')->nullable()->after('alamat_pengiriman');
            }
            if (!Schema::hasColumn('pesanans', 'gambar_makanan')) {
                $table->string('gambar_makanan')->nullable()->after('nama_penerima');
            }
            if (!Schema::hasColumn('pesanans', 'status_courier')) {
                $table->string('status_courier')->default('on-going')->after('gambar_makanan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('pesanans', 'nama_penerima')) {
                $columns[] = 'nama_penerima';
            }
            if (Schema::hasColumn('pesanans', 'gambar_makanan')) {
                $columns[] = 'gambar_makanan';
            }
            if (Schema::hasColumn('pesanans', 'status_courier')) {
                $columns[] = 'status_courier';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

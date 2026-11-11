<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus status_driver dari item_pesanans dan
     * hapus status_courier + gambar_makanan dari pesanans.
     * Status pesanan cukup menggunakan kolom 'status' di tabel pesanans
     * dengan nilai: 'new', 'picked up', 'complete'.
     */
    public function up(): void
    {
        // Hapus status_driver dari item_pesanans
        if (Schema::hasColumn('item_pesanans', 'status_driver')) {
            Schema::table('item_pesanans', function (Blueprint $table) {
                $table->dropColumn('status_driver');
            });
        }

        // Hapus status_courier dan gambar_makanan dari pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            $toDrop = [];
            if (Schema::hasColumn('pesanans', 'status_courier')) {
                $toDrop[] = 'status_courier';
            }
            if (Schema::hasColumn('pesanans', 'gambar_makanan')) {
                $toDrop[] = 'gambar_makanan';
            }
            if (!empty($toDrop)) {
                $table->dropColumn($toDrop);
            }
        });

        // Pastikan kolom status di pesanans menggunakan nilai default 'new'
        Schema::table('pesanans', function (Blueprint $table) {
            $table->string('status')->default('new')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan status_driver ke item_pesanans
        if (!Schema::hasColumn('item_pesanans', 'status_driver')) {
            Schema::table('item_pesanans', function (Blueprint $table) {
                $table->string('status_driver')->default('belum_diambil_driver')->after('kuantitas');
            });
        }

        // Kembalikan status_courier dan gambar_makanan ke pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanans', 'gambar_makanan')) {
                $table->string('gambar_makanan')->nullable();
            }
            if (!Schema::hasColumn('pesanans', 'status_courier')) {
                $table->string('status_courier')->default('on-going');
            }
        });
    }
};

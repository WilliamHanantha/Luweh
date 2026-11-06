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
        Schema::create('daftar_makanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_makanan');
            $table->string('deskripsi');
            $table->decimal('harga', 10, 2);
            $table->integer('stok');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_makanans');
    }
};

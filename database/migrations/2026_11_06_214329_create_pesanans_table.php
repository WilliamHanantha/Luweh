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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('fnb_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('courier_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('Keranjang');
            $table->string('metode_pengiriman')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->decimal('ongkir', 10, 2)->default(0);
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};

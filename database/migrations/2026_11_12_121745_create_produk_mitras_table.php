
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_mitras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restoran_id')
                ->constrained('restorans')
                ->restrictOnDelete();

            $table->string('nama_produk', 100);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();

            $table->unsignedInteger('poin_dibutuhkan');
            $table->unsignedInteger('stok')->default(0);

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_mitras');
    }
};

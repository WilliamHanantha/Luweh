
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penukaran_points', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('produk_mitra_id')
                ->constrained('produk_mitras')
                ->restrictOnDelete();

            $table->unsignedInteger('jumlah_poin_digunakan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penukaran_points');
    }
};

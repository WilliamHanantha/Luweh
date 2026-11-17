
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikat_digitals', function (Blueprint $table) {
            $table->id();

            // Menghubungkan sertifikat dengan Kontribusi
            $table->foreignId('kontribusi_id')
                ->constrained('kontribusis');


             // Total Food Saved saat sertifikat diterbitkan
            $table->double('total_food_saved')->nullable();


            // Lokasi file sertifikat yang dihasilkan
            $table->string('file_path');

            // Waktu penerbitan sertifikat
            $table->timestamp('issued_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat_digitals');
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SertifikatDigital extends Model
{
    // Nama tabel di database
    protected $table = 'sertifikat_digitals';

    // Atribut yang boleh diisi melalui Model

    protected $fillable = [
        'kontribusi_id',
        'nama_penerima',
        'total_food_saved',
        'file_path',
        'issued_at',
    ];


    // Konversi tipe data
    protected function casts(): array
    {
        return [
            'total_food_saved' => 'double',
            'issued_at' => 'datetime',
        ];
    }

    // Relasi: satu sertifikat milik satu Kontribusi
    public function kontribusi(): BelongsTo
    {
        return $this->belongsTo(
            Kontribusi::class,
            'kontribusi_id'
        );
    }
}

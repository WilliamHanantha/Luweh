<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    protected $fillable = [
        'pesanan_id',
        'daftar_makanan_id',
        'kuantitas',
        'status_driver',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function daftarMakanan()
    {
        return $this->belongsTo(DaftarMakanan::class);
    }
}

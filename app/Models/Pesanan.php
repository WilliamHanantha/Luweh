<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'user_id',
        'restoran_id',
        'status',
        'metode_pengiriman',
        'alamat_pengiriman',
        'nama_penerima',
        'gambar_makanan',
        'status_courier',
        'ongkir',
        'total_harga',
        'tanggal_pesanan',
    ];

    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }

    public function itemPesanans()
    {
        return $this->hasMany(ItemPesanan::class);
    }
}

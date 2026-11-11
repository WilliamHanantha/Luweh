<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'user_id',
        'fnb_user_id',
        'courier_user_id',
        'status',
        'metode_pengiriman',
        'alamat_pengiriman',
        'nama_penerima',
        'ongkir',
        'total_harga',
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restoran extends Model
{
    protected $fillable = ['nama_resto', 'alamat', 'jarak', 'rating'];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontribusi extends Model
{
    use HasFactory;

    protected $table = 'kontribusis';

    protected $fillable = [
        'user_id',
        'total_point',
    ];

    protected function casts(): array
    {
        return [
            'total_point' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

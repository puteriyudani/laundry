<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    protected $fillable = [
        'jenis',
        'kg',
        'harga',
        'status',
        'hari'
    ];

    protected $casts = [
        'harga' => 'float',  // atau 'integer' jika harga selalu integer
    ];
}

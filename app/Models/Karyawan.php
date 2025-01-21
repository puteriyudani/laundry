<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $fillable = [
        'name',
        'email',
        'alamat',
        'no_telp',
        'kelamin',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'karyawan_id');
    }
}

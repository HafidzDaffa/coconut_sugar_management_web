<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'latitude',
        'longitude',
        'penanggung_jawab',
        'telepon',
        'email',
        'status',
        'kapasitas_harian_kg',
        'keterangan',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'kapasitas_harian_kg' => 'float',
    ];
}

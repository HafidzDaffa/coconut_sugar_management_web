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
        'no_badan_hukum',
        'tanggal_berdiri',
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
        'tanggal_berdiri' => 'date:Y-m-d',
        'latitude' => 'float',
        'longitude' => 'float',
        'kapasitas_harian_kg' => 'float',
    ];
}

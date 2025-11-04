<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gratification extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelapor',
        'nomor_identitas',
        'alamat',
        'pekerjaan',
        'telepon',
        'email',
        'judul_laporan',
        'uraian_laporan',
        'data_dukung',
        'status',
        'jawaban',
        'kode_register',
        'waktu_publish',
    ];

    protected $casts = [
        'waktu_publish' => 'datetime',
    ];
}
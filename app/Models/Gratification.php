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
        'kode_register',
    ];

    public function processes()
    {
        return $this->hasMany(GratificationProcess::class, 'gratification_id');
    }
    
    public function latestProcess()
    {
        return $this->hasOne(GratificationProcess::class, 'gratification_id')
                   ->latestOfMany();
    }
}
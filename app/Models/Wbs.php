<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wbs extends Model
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
        'violation_id',
        'kode_register',
    ];

    protected $casts = [
        'data_dukung' => 'string',
        'violation_id' => 'integer',
    ];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }

    public function processes()
    {
        return $this->hasMany(WbsProcess::class, 'wbs_id');
    }
}
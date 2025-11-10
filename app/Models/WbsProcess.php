<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbsProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'wbs_id',
        'status',
        'jawaban',
        'jawaban_lampiran',
        'waktu_publish',
    ];

    protected $casts = [
        'wbs_id' => 'integer',
        'waktu_publish' => 'datetime',
    ];

    public function wbs()
    {
        return $this->belongsTo(Wbs::class);
    }
}
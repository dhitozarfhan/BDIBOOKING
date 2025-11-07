<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GratificationProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'gratification_id',
        'status',
        'jawaban',
        'jawaban_lampiran',
        'waktu_publish',
    ];

    protected $casts = [
        'waktu_publish' => 'datetime',
    ];

    public function gratification()
    {
        return $this->belongsTo(Gratification::class);
    }
}

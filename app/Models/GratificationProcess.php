<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GratificationProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'gratification_id',
        'response_status_id',
        'user_id',
        'answer',
        'answer_attachment',
    ];

    protected $casts = [
        'response_status_id' => \App\Enums\ResponseStatus::class,
    ];

    public function gratification()
    {
        return $this->belongsTo(Gratification::class);
    }

    public function responseStatus()
    {
        return $this->belongsTo(ResponseStatus::class);
    }
}

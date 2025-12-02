<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbsProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'wbs_id',
        'response_status_id',
        'user_id',
        'answer',
        'answer_attachment',
    ];

    protected $casts = [
        'wbs_id' => 'integer',
        'published_at' => 'datetime',
        'response_status_id' => \App\Enums\ResponseStatus::class,
    ];

    public function wbs()
    {
        return $this->belongsTo(Wbs::class);
    }

    public function responseStatus()
    {
        return $this->belongsTo(ResponseStatus::class);
    }
}
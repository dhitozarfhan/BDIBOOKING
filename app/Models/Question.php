<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'content',
        'name',
        'mobile',
        'email',
        'registration_code',
    ];

    /**
     * Get the report's processing record.
     */
    public function process(): MorphOne
    {
        return $this->morphOne(ReportProcess::class, 'reportable');
    }
}
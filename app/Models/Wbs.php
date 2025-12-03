<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Wbs extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_name',
        'identity_number',
        'identity_card_attachment',
        'address',
        'occupation',
        'phone',
        'email',
        'report_title',
        'report_description',
        'attachment',
        'violation_id',
        'registration_code',
    ];

    protected $casts = [
        'attachment' => 'string',
        'violation_id' => 'integer',
    ];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }

    /**
     * Get the report's processing record.
     */
    public function process(): MorphOne
    {
        return $this->morphOne(ReportProcess::class, 'reportable');
    }
}

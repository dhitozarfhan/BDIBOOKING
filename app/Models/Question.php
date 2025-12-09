<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany; // Added

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_name',
        'content',
        'report_title',
        'mobile',
        'email',
        'identity_number',
        'identity_card_attachment',
        'registration_code',
    ];

    /**
     * Get the latest processing record for the report.
     */
    public function process(): MorphOne
    {
        return $this->morphOne(ReportProcess::class, 'reportable')->latestOfMany();
    }

    /**
     * Get all processing records for the report (history).
     */
    public function reportProcesses(): MorphMany
    {
        return $this->morphMany(ReportProcess::class, 'reportable');
    }
}
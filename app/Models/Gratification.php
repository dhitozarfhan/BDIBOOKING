<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany; // Added

class Gratification extends Model
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
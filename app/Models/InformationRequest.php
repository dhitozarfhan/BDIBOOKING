<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class InformationRequest extends Model
{
    protected $fillable = [
        'reporter_name',
        'id_card_number',
        'address',
        'occupation',
        'mobile',
        'email',
        'report_title',
        'used_for',
        'grab_method',
        'delivery_method',
        'rule_accepted',
        'ip_address',
        'user_agent',
        'registration_code',
    ];

    protected $casts = [
        'grab_method' => 'array',
        'delivery_method' => 'array',
        'rule_accepted' => 'boolean',
    ];

    /**
     * Get the report's processing record.
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
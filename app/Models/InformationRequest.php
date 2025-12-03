<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class InformationRequest extends Model
{
    protected $fillable = [
        'name',
        'id_card_number',
        'address',
        'occupation',
        'mobile',
        'email',
        'content',
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
        return $this->morphOne(ReportProcess::class, 'reportable');
    }
}
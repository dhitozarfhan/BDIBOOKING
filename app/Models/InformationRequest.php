<?php

namespace App\Models;

use App\Enums\InformationRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
        'notes',
        'processed_by',
        'processed_at',
        'rule_accepted',
        'ip_address',
        'user_agent',
        'registration_code',
    ];

    protected $casts = [
        'grab_method' => 'array',
        'delivery_method' => 'array',
        'status' => InformationRequestStatus::class,
        'rule_accepted' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

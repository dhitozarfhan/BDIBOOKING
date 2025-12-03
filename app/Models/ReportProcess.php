<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ReportProcess extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reportable_id',
        'reportable_type',
        'user_id',
        'response_status_id',
        'answer',
        'answer_attachment',
        'disposition_to_employee_id',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_completed' => 'boolean',
    ];

    /**
     * Get the parent reportable model (Gratification, Wbs, Question, etc.).
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function responseStatus(): BelongsTo
    {
        return $this->belongsTo(ResponseStatus::class);
    }

    public function dispositionTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'disposition_to_employee_id');
    }
}
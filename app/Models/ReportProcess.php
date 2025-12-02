<?php

namespace App\Models;

use App\Enums\ReportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportProcess extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_type',
        'gratification_id',
        'wbs_id',
        'question_id',
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
        'report_type' => ReportType::class,
        'is_completed' => 'boolean',
    ];

    /**
     * Get the parent reportable model (Gratification, Wbs, or Question).
     */
    public function getReportableAttribute()
    {
        switch ($this->report_type) {
            case ReportType::GRATIFICATION:
                return $this->gratification;
            case ReportType::WBS:
                return $this->wbs;
            case ReportType::PUBLIC_COMPLAINT:
                return $this->question;
            default:
                return null;
        }
    }

    public function gratification(): BelongsTo
    {
        return $this->belongsTo(Gratification::class);
    }

    public function wbs(): BelongsTo
    {
        return $this->belongsTo(Wbs::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
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

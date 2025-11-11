<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function processes()
    {
        return $this->hasMany(WbsProcess::class, 'wbs_id');
    }
}
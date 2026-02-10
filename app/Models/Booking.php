<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'participant_id',
        'bookable_id',
        'bookable_type',
        'status',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function bookable()
    {
        return $this->morphTo();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}

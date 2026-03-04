<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'bookable_id',
        'bookable_type',
        'booking_type',
        'quantity',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bookable()
    {
        return $this->morphTo();
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function participantData()
    {
        return $this->hasMany(ParticipantData::class);
    }
}

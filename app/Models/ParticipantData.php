<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantData extends Model
{
    protected $table = 'participant_data';

    protected $fillable = [
        'customer_id',
        'booking_id',
        'required_field_id',
        'value',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function requiredField()
    {
        return $this->belongsTo(RequiredField::class);
    }
}

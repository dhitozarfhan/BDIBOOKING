<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'id_booking',
        'customer_id',
        'bookable_id',
        'bookable_type',
        'assigned_room_id',
        'booking_type',
        'quantity',
        'status',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedRoom()
    {
        return $this->belongsTo(Room::class, 'assigned_room_id');
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

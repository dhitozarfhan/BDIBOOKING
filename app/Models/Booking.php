<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class Booking extends Model
{
    protected $fillable = [
        'id_booking',
        'customer_id',
        'user_id',
        'bookable_id',
        'bookable_type',
        'assigned_room_id',
        'contact_name',
        'contact_email',
        'contact_phone',
        'institution',
        'total_price',
        'booking_type',
        'quantity',
        'status',
        'start_date',
        'end_date',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->id_booking)) {
                $prefix = $booking->bookable_type === Property::class ? 'PRP' : 'TRN';
                $booking->id_booking = 'BKG-' . $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            }
        });
    }

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

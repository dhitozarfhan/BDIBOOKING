<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAPK extends Model
{
    protected $fillable = [
        'property_id', 'user_id', 'contact_name', 'contact_email', 'contact_phone', 
        'institution', 'start_date', 'end_date', 'status'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->hasOneThrough(
            PropertyType::class,
            Property::class,
            'id', // Foreign key on properties table...
            'id', // Foreign key on property_types table...
            'property_id', // Local key on bookings table...
            'property_type_id' // Local key on properties table...
        );
    }
}

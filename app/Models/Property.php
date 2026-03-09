<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'property_type_id',
        'name',
        'description',
        'capacity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}

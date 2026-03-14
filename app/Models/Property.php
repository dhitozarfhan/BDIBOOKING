<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'property_type_id',
        'category',
        'name',
        'description',
        'capacity',
        'price',
        'status',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'image' => 'array',
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

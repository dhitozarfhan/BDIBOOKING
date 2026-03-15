<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
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


    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    /**
     * Get the number of available rooms for this property.
     */
    public function getAvailableRoomsCountAttribute()
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    /**
     * Get the total number of rooms for this property.
     */
    public function getTotalRoomsCountAttribute()
    {
        return $this->rooms()->count();
    }
}

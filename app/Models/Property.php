<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'assigned_room_id');
    }
}

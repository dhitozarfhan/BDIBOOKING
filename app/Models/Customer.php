<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'party_id',
        'name',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Bookings where this customer is a participant (not necessarily the one who booked).
     */
    public function participations()
    {
        return $this->hasMany(Participant::class);
    }

    public function participantData()
    {
        return $this->hasMany(ParticipantData::class);
    }
}

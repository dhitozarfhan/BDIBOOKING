<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Participant extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'birth_place',
        'birth_date',
        'gender_id',
        'religion_id',
        'blood_type',
        'phone',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'occupation_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'password' => 'hashed',
    ];

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function province()
    {
        return $this->belongsTo(Area::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(Area::class, 'city_id');
    }

    public function district()
    {
        return $this->belongsTo(Area::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(Area::class, 'village_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function participantData()
    {
        return $this->hasMany(ParticipantData::class);
    }
}

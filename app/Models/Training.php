<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    const TYPE_3IN1 = '3in1';
    const TYPE_PNBP = 'pnbp';

    protected $fillable = [
        'title',
        'type',
        'description',
        'image',
        'start_date',
        'end_date',
        'location',
        'price',
        'quota',
        'is_published',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}

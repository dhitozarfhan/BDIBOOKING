<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'booking_id',
        'certificate_number',
        'file_path',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Auto-generate certificate number.
     * Format: SERT/{YEAR}/{SEQUENTIAL_NUMBER}
     */
    public static function generateNumber(): string
    {
        $year = date('Y');
        $lastCert = static::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $sequence = $lastCert
            ? (int) substr($lastCert->certificate_number, -4) + 1
            : 1;

        return sprintf('SERT/%s/%04d', $year, $sequence);
    }
}

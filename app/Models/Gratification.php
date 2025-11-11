<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gratification extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_name',
        'identity_number',
        'address',
        'occupation',
        'phone',
        'email',
        'report_title',
        'report_description',
        'attachment',
        'registration_code',
    ];

    public function processes()
    {
        return $this->hasMany(GratificationProcess::class, 'gratification_id');
    }
    
    public function latestProcess()
    {
        return $this->hasOne(GratificationProcess::class, 'gratification_id')
                   ->latestOfMany();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_COMPANY = 'company';

    protected $fillable = [
        'type',
        'company_name',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $table = 'property_types';

    protected $fillable = [
        'name',
        'description',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }


}

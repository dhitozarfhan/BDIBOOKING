<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the folders for the classification.
     */
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Get the readable name attribute.
     */
    public function getReadableNameAttribute()
    {
        $name = $this->name;
        if (is_array($name)) {
            return $name['id'] ?? $name['en'] ?? reset($name);
        }
        return $name;
    }
}
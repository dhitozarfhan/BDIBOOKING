<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
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
     * Get the documents for the segment.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
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
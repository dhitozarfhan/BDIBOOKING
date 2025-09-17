<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = [
        'name',
        'classification_id',
        'location_id',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the classification that owns the folder.
     */
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * Get the location that owns the folder.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the documents for the folder.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Studio15\FilamentTree\Concerns\InteractsWithTree;

class Classification extends Model
{
    use NodeTrait;
    use InteractsWithTree;
    
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

    public static function getTreeLabelAttribute() : string
    {
        return 'code_name';
    }

    public function getCodeNameAttribute(): string
    {
        return $this->code. ' '. $this->name;
    }

}
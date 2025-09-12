<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Studio15\FilamentTree\Concerns\InteractsWithTree;

class Segment extends Model
{
    use NodeTrait;
    use InteractsWithTree;

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the documents for the segment.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
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
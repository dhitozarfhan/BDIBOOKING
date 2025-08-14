<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Slideshow extends Model
{
    use HasTranslations;

    protected $fillable = [
        'link_type_id',
        'article_id',
        'image',
        'title',
        'description',
        'path',
        'target_blank',
        'is_active',
        'sort'
    ];

    public $translatable = ['title', 'description'];

    public $casts = [
        'title' => 'array',
        'description' => 'array'
    ];

    public function linkType()
    {
        return $this->belongsTo(LinkType::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($record) {
            $record->sort = Slideshow::count() + 1;
        });

        //delete remove image from storage
        static::deleted(function ($record) {
            if(Storage::exists($record->image)) {
                Storage::delete($record->image);
            }
        });
    }
}

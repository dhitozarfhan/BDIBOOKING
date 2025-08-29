<?php

namespace App\Models;

use App\Enums\LinkType as EnumsLinkType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

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


    public function getLinkAttribute(): string
    {
        return $this->link_type_id == EnumsLinkType::Article->value ? route('articles.show', ['slug' => Str::kebab($this->article->title).'-'.$this->article_id, 'article_type' => $this->article->articleType->slug]) : (
            $this->link_type_id == EnumsLinkType::Internal->value ? url($this->path) :
            ($this->link_type_id == EnumsLinkType::Empty->value ? '#' : $this->path)
        );
    }

    public $casts = [
        'title' => 'array',
        'description' => 'array'
    ];

    public function linkType()
    {
        return $this->belongsTo(LinkType::class);
    }

    public function article() {
        return $this->belongsTo(Article::class, 'article_id');
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

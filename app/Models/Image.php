<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Image extends Model
{

    use HasTranslations;

    public $translatable = ['description'];

    protected $fillable = ['article_id', 'path', 'description', 'sort'];

    protected $casts = [
        'description' => 'array',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    protected static function booted(): void
    {
        static::creating(function ($record) {
            $record->sort = Image::where('article_id', $record->article_id)->count() + 1;
        });

    }

}

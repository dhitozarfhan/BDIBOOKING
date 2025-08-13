<?php

namespace App\Models;

use App\Enums\ArticleType as EnumsArticleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasTranslations;
    protected $fillable = ['category_id', 'article_type_id', 'author_id', 'image', 'title', 'summary', 'content', 'hit', 'is_active', 'published_at',
        'sort', 'year', 'files', 'original_files'];

    public $translatable = ['title', 'summary', 'content'];

    protected $casts = [
        'title' => 'array',
        'summary' => 'array',
        'content' => 'array',
        'files' => 'array',
        'original_files' => 'json',
    ];

    public function articleType() {
        return $this->belongsTo(ArticleType::class, 'article_type_id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author() {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id')->orderBy('tags.name->' . app()->getLocale());
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'article_id');
    }

    public function incrementHit()
    {
        $this->increment('hit');
    }

    protected static function booted(): void
    {
        static::creating(function ($record) {
            if($record->article_type_id == EnumsArticleType::Information->value){
                Article::where('article_type_id', EnumsArticleType::Information->value)->where('category_id', $record->category_id)->increment('sort');
            }
        });

        static::deleted(function ($record) {
            if(Storage::exists($record->image)) {
                Storage::delete($record->image);
            }
        });

    }
}

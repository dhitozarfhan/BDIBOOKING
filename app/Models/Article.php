<?php

namespace App\Models;

use App\Enums\ArticleType as EnumsArticleType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
    
    protected $appends = ['slug']; // agar otomatis ikut saat toArray()

    public function getSlugAttribute(): string
    {
        $locale = app()->getLocale();
        $title = '';

        // Prefer Spatie's getTranslation if available
        if (method_exists($this, 'getTranslation')) {
            try {
                $title = (string) $this->getTranslation('title', $locale);
            } catch (\Throwable $e) {
                $title = '';
            }
        }

        // Fallbacks if translation not available or empty
        if ($title === '') {
            // Try raw attributes (JSON stored)
            if (isset($this->attributes['title'])) {
                $raw = $this->attributes['title'];
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) {
                        $title = (string) ($decoded[$locale] ?? reset($decoded) ?? $raw);
                    } else {
                        $title = (string) $raw;
                    }
                } elseif (is_array($raw)) {
                    $title = (string) ($raw[$locale] ?? reset($raw) ?? '');
                } else {
                    $title = (string) $raw;
                }
            } elseif (isset($this->title)) {
                if (is_array($this->title)) {
                    $title = (string) ($this->title[$locale] ?? reset($this->title) ?? '');
                } else {
                    $title = (string) $this->title;
                }
            }
        }

        $title = trim($title);
        $id    = (string) ($this->attributes['id'] ?? $this->id ?? '');

        if ($title === '') {
            return $id !== '' ? $id : '';
        }

        return \Illuminate\Support\Str::slug($title) . ($id !== '' ? "-{$id}" : '');
    }

    /**
     * Ambil ID artikel dari slug "something-123".
     */
    public static function idFromSlug(string $slug): ?int
    {
        // cara cepat: ambil token terakhir setelah '-'
        $parts = explode('-', $slug);
        $last  = end($parts);
        return ctype_digit($last) ? (int) $last : null;
    }

    #[Scope]
    public function published(Builder $query)
    {
        return $query->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

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

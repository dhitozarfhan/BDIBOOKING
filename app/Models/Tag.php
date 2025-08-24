<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'is_active'];

    public $translatable = ['name'];
    
    protected $casts = [
        'name' => 'array'
    ];

    protected $appends = ['slug']; // agar otomatis tersedia saat toArray()

    public function getSlugAttribute(): string
    {
        $locale = app()->getLocale();
        $name = '';

        // Prefer Spatie's getTranslation if available
        if (method_exists($this, 'getTranslation')) {
            try {
                $name = (string) $this->getTranslation('name', $locale);
            } catch (\Throwable $e) {
                $name = '';
            }
        }

        // Fallbacks if translation not available or empty
        if ($name === '') {
            // Try raw attributes (JSON stored)
            if (isset($this->attributes['name'])) {
                $raw = $this->attributes['name'];
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) {
                        $name = (string) ($decoded[$locale] ?? reset($decoded) ?? $raw);
                    } else {
                        $name = (string) $raw;
                    }
                } elseif (is_array($raw)) {
                    $name = (string) ($raw[$locale] ?? reset($raw) ?? '');
                } else {
                    $name = (string) $raw;
                }
            } elseif (isset($this->name)) {
                if (is_array($this->name)) {
                    $name = (string) ($this->name[$locale] ?? reset($this->name) ?? '');
                } else {
                    $name = (string) $this->name;
                }
            }
        }

        $name = trim($name);
        $id    = (string) ($this->attributes['id'] ?? $this->id ?? '');

        if ($name === '') {
            return $id !== '' ? $id : '';
        }

        return \Illuminate\Support\Str::slug($name) . ($id !== '' ? "-{$id}" : '');
    }

    public static function idFromSlug(string $slug): ?int
    {
        $parts = explode('-', $slug);
        $last  = end($parts);
        return ctype_digit($last) ? (int) $last : null;
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag', 'tag_id', 'article_id');
    }

    protected static function booted(): void
    {
        static::deleted(function ($record) {
            // Handle any cleanup if necessary when a tag is deleted
            //delete article_tag records associated with this tag
            DB::table('article_tag')->where('tag_id', $record->id)->delete();
        });
    }

}

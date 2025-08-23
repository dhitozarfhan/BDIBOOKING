<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    //
    protected $fillable = ['name', 'parent_id', 'category_type_id', 'sort', 'is_root', 'is_active'];

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
        return $this->hasMany(Article::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id');
    }

    protected static function booted(): void
    {
        static::creating(function ($record) {
            $record->sort = $record->category_type_id == CategoryType::Article->value ? Category::where('category_type_id', $record->category_type_id)->count() + 1 : Category::where('category_type_id', $record->category_type_id)->where('parent_id', $record->parent_id)->count() + 1 ;
        });


        static::deleted(function ($record) {
            Category::where('category_type_id', $record->category_type_id)->where('sort', '>', $record->sort)->decrement('sort');

            $root = $record->category_type_id == CategoryType::Article->value ? Category::where('category_type_id', $record->category_type_id)->where('is_root', true)->first() :
            Category::where('parent_id', $record->parent_id)->where('category_type_id', $record->category_type_id)->where('is_root', true)->first();
            
            if($root) {
                Article::where('category_id', $record->id)->update(['category_id' => $root->id]);
            }
        });
    }
}
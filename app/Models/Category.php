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
<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    //
    protected $fillable = ['name', 'sort', 'is_root', 'is_active'];

    public $translatable = ['name'];

    protected $casts = [
        'name' => 'array'
    ];

    protected static function booted(): void
    {
        static::creating(function ($record) {
            $record->sort = Category::count() + 1;
        });


        static::deleted(function ($record) {
            Category::where('sort', '>', $record->sort)->decrement('sort');

            $root = Category::where('is_root', true)->first();
            
            if($root) {
                Article::where('category_id', $record->id)->update(['category_id' => $root->id]);
            }
        });
    }
}
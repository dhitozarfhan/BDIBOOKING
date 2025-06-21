<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    //
    protected $fillable = [ 'core_id', 'category_type_id', 'name', 'sort', 'is_root', 'is_active'];

    public $translatable = ['name'];

    protected $casts = [
        'name' => 'array'
    ];

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class);
    }

    public function core()
    {
        return $this->belongsTo(Core::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($record) {
            $record->sort = Category::where('category_type_id', $record->category_type_id)->count() + 1;
        });


        static::deleted(function ($record) {
            Category::where('category_type_id', $record->category_type_id)
                ->where('sort', '>', $record->sort)
                ->decrement('sort');

            $root = Category::where('category_type_id', $record->category_type_id)
                ->where('is_root', true)
                ->first();
            
            if($record->category_type_id == CategoryType::News->value && $root) {
                News::where('category_id', $record->id)->update(['category_id' => $root->id]);
            }
            if($record->category_type_id == CategoryType::Blog->value && $root) {
                Blog::where('category_id', $record->id)->update(['category_id' => $root->id]);
            }
            // if($record->category_type_id == CategoryType::Gallery->value && $root) {
            //     Gallery::where('category_id', $record->id)->update(['category_id' => $root->id]);
            // }
            // if($record->category_type_id == CategoryType::Event->value && $root) {
            //     Event::where('category_id', $record->id)->update(['category_id' => $root->id]);
            // }
        });
    }
}
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

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Blog extends Model
{
    use HasTranslations;
    protected $fillable = ['category_id', 'image', 'title', 'summary', 'content', 'hit', 'is_active'];

    public $translatable = ['title', 'summary', 'content'];

    protected $casts = [
        'title' => 'array',
        'summary' => 'array',
        'content' => 'array'
    ];


    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function incrementHit()
    {
        $this->increment('hit');
    }

    // public function scopePublished($query)
    // {
    //     $query->where('time_stamp', '<=', Carbon::now());
    // }

    // public function getThumbnailImage()
    // {
    //     $isUrl = str_contains($this->image, 'http');

    //     return $isUrl ? $this->image : Storage::url($this->image);
    // }
}

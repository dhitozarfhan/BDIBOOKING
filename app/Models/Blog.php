<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';
    protected $primaryKey = 'blog_id';
    protected $fillable = ['category_id', 'time_stamp', 'image', 'en_title', 'id_title', 'en_summary', 'id_summary', 'en_content', 'id_content', 'hit', 'is_active'];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getThumbnailImage()
    {
        $isUrl = str_contains($this->image, 'http');

        return $isUrl ? $this->image : Storage::url($this->image);
    }
}

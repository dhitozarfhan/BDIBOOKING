<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slideshow extends Model
{
    use HasFactory;

    protected $table = 'slideshows';

    protected $fillable = [
        'image',
        'en_title',
        'id_title',
        'en_description',
        'id_description',
        'path'
    ];

    public function getSlideImage()
    {
        $isUrl = str_contains($this->image, 'http');

        return $isUrl ? $this->image : Storage::url($this->image);
    }
}

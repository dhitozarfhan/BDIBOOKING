<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Seminar extends Model
{
    use HasFactory;

    protected $table = 'seminar';
    protected $primaryKey = 'seminar_id';
    public $timestamps = false;

    protected $fillable = [
        'image',
        'title',
        'price',
        'description'
    ];

    public function getThumbnailImage()
    {
        $isUrl = str_contains($this->image, 'http');

        return $isUrl ? $this->image : Storage::url($this->image);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'document',
    ];

    public function getDocument()
    {
        $isUrl = str_contains($this->document, 'http');

        return $isUrl ? $this->document : Storage::url($this->document);
    }
}

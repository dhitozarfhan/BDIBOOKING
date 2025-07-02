<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'is_active'];

    public $translatable = ['name'];
    
    protected $casts = [
        'name' => 'array'
    ];

}

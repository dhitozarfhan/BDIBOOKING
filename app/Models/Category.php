<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'title',
        'slug',
    ];

    public function posts() {
        return $this->belongsToMany(Post::class);
    }

    public function types() {
        return $this->belongsTo(Type::class, 'type_id');
    }
}

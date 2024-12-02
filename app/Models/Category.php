<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $fillable = [ 'core_id', 'type', 'en_name', 'id_name', 'sort', 'is_root', 'is_active'];

    public function cores() {
        return $this->belongsTo(Core::class, 'core_id');
    }

    public function news() {
        return $this->hasMany(News::class, 'category_id');
    }

    public function blog() {
        return $this->hasMany(Blog::class, 'category_id');
    }
}

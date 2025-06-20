<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    //
    protected $fillable = [ 'core_id', 'category_type_id_id', 'name', 'sort', 'is_root', 'is_active'];

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
}
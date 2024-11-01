<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'information';
    protected $primaryKey = 'information_id';
    protected $fillable = ['category_id', 'time_stamp', 'file', 'year', 'en_title', 'id_title',
                            'en_summary', 'id_summary', 'en_content', 'id_content', 'sort', 'is_active'];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

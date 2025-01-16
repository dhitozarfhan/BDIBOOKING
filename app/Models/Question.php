<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'question';
    protected $primaryKey = 'question_id';
    public $timestamps = false;
    protected $fillable = [
        'question_code',
        'type',
        'category_id',
        'name',
        'id_card_number',
        'address',
        'occupation',
        'mobile',
        'email',
        'website',
        'subject',
        'content',
        'used_for',
        'grab_method',
        'delivery_method',
        'supervisor_id',
        'answer',
        'attachment',
        'status',
        'time_insert',
        'time_update',
        'time_publish',
    ];
}

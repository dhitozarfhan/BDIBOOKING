<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequiredField extends Model
{
    protected $fillable = [
        'name',
        'is_file',
        'sort'
    ];

    protected $casts = [
        'is_file' => 'boolean',
    ];

    public function trainings()
    {
        return $this->belongsToMany(Training::class);
    }
}

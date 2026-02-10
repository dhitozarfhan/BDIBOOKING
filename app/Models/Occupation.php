<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $fillable = ['name'];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}

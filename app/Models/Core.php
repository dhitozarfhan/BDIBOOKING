<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Core extends Model
{
    use HasFactory;

    protected $table = 'cores';
    protected $primaryKey = 'core_id';
    protected $fillable = ['type', 'slug', 'en_name', 'id_name', 'icon', 'sort'];
}

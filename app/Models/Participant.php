<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participant';
    protected $primaryKey = 'participant_id';

    public $timestamps = true;

    protected $fillable = [
        'seminar_id',
        'name',
        'no_wa',
        'email',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'seminar_id', 'seminar_id');
    }
}

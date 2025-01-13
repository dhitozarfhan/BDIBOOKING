<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    use HasFactory;

    protected $table = 'page';
    protected $primaryKey = 'page_id';
    public $timestamps = false;
    protected $fillable = ['admin_id', 'slug', 'time_stamp', 'en_title', 'id_title', 'en_summary',
                            'id_summary', 'en_content', 'id_content', 'hit', 'is_active',
                            'enable_comment', 'auto_accept_comment', 'email_notification_comment'];

    public function incrementHit()
    {
        $this->increment('hit');
    }
}

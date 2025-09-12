<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'folder_id',
        'segment_id',
        'file_path',
        'description',
        'published_at',
        'active_retention',
        'inactive_retention',
        'condition',
    ];

    protected $casts = [
        'published_at' => 'date',
        'condition' => 'boolean',
    ];

    /**
     * Get the folder that owns the document.
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Get the segment that owns the document.
     */
    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    /**
     * Get the accounts for the document.
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'account_document');
    }

    /**
     * Get the employees who borrowed the document.
     */
    public function borrowers()
    {
        return $this->belongsToMany(Employee::class, 'document_employee')
                    ->withPivot([
                        'borrow_date',
                        'due_date',
                        'return_date',
                        'status',
                        'needs',
                        'token',
                        'notes'
                    ])
                    ->withTimestamps();
    }
}
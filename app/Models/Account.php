<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Get the formatted code and name for display.
     */
    protected function getFormattedCodeNameAttribute(): string
    {
        return sprintf('%s--%s', $this->code, $this->name);
    }

    /**
     * Get the documents for the account.
     * 
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class, 'account_document')->using(AccountDocument::class);
    }
}
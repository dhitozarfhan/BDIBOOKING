<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountDocument extends Pivot
{
    protected $table = 'account_document';
    
    public $incrementing = true;
    
    protected $fillable = [
        'account_id',
        'document_id',
    ];

    /**
     * Get the account for this account-document relationship.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the document for this account-document relationship.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
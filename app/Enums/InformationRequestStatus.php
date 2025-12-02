<?php

namespace App\Enums;

enum InformationRequestStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Approved = 'approved';
    case Rejected = 'rejected';
    
    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::Pending => 'warning',
            self::Processing => 'info',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}

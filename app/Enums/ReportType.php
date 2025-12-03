<?php

namespace App\Enums;

enum ReportType: string
{
    case GRATIFICATION = 'gratification';
    case WBS = 'wbs';
    case PUBLIC_COMPLAINT = 'public_complaint';
    case INFORMATION_REQUEST = 'information_request'
}

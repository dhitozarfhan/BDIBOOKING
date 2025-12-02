<?php

namespace App\Enums;

enum ReportType: string
{
    case GRATIFICATION = 'gratification';
    case WBS = 'wbs';
    case PUBLIC_COMPLAINT = 'public_complaint';
}

<?php

namespace App\Enums;

enum EmployeeStatusCode :int
{   
    case CPNS       = 1;
    case PNS        = 2;
    case Pensiun    = 3;
    case PPPK       = 4;
    case NonPNS     = 99;
}

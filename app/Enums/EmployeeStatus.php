<?php

namespace App\Enums;

enum EmployeeStatus :int
{   
    case CPNS       = 1;
    case PNS        = 2;
    case Pensiun    = 3;
    case Mutation   = 4;
    case PPPK       = 100;
    case NonPNS     = 99;
}

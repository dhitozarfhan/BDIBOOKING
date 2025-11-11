<?php

namespace App\Enums;

enum ResponseStatus: int
{
    // I=Initiation, P=Process, D=Disposition, T=Termination
    case Initiation = 1;
    case Process = 2;
    case Disposition = 3;
    case Termination = 4;
}

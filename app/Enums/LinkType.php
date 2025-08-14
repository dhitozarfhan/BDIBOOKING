<?php

namespace App\Enums;

enum LinkType: int
{
    case Article = 1;
    case Internal = 2;
    case External = 3;
    case Module = 4;
    case Empty = 99;
}

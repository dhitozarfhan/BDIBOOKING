<?php

namespace App\Enums;

enum ArticleType: int
{
    case News        = 1;
    case Gallery     = 2;
    case Page        = 3;
    case Information = 4;
    // case Question    = 5;
    // case Request     = 6;
    // case Wbs         = 7;
    // case Service     = 8;
}
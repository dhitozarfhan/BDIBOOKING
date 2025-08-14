<?php

namespace App\Enums;

enum ArticleType: int
{
    case News        = 1;
    case Blog        = 2;
    case Gallery     = 3;
    case Page        = 4;
    case Information = 5;
    // case Question    = 6;
    // case Request     = 7;
    // case Wbs         = 8;
    // case Service     = 9;
}
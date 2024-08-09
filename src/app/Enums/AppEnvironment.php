<?php

declare(strict_types = 1);

namespace App\Enums;

enum AppEnvironment: string
{
    case Development = 'development';
    case Staging = 'staging';
    case Production  = 'production';
}
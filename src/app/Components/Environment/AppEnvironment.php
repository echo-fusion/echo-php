<?php

declare(strict_types=1);

namespace App\Components\Environment;

enum AppEnvironment: string
{
    case Development = 'development';
    case Staging = 'staging';
    case Production  = 'production';
}

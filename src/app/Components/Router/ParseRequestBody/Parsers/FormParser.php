<?php

declare(strict_types=1);

namespace App\Components\Router\ParseRequestBody\Parsers;

use App\Components\Router\ParseRequestBody\ParserInterface;

class FormParser implements ParserInterface
{
    public function parse(string $bodyString): array
    {
        parse_str($bodyString, $output);
        return $output;
    }
}
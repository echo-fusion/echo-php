<?php

declare(strict_types=1);

namespace App\Components\Router\ParseRequestBody\Parsers;

use App\Components\Router\ParseRequestBody\ParserInterface;

class JsonParser implements ParserInterface
{
    public function parse(string $bodyString): array
    {
        return json_decode($bodyString, true);
    }
}
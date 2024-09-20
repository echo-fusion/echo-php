<?php

declare(strict_types=1);

namespace App\Components\Router\ParseRequestBody\Parsers;

use App\Components\Router\ParseRequestBody\ParserInterface;

class XmlParser implements ParserInterface
{
    public function parse(string $bodyString): array
    {
        $xml = simplexml_load_string($bodyString);
        $output = json_decode(json_encode($xml), true);
        return $output;
    }
}
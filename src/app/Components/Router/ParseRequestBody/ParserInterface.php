<?php

declare(strict_types=1);

namespace App\Components\Router\ParseRequestBody;

interface ParserInterface
{
    public function parse(string $bodyString): array;
}
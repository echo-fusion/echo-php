<?php

declare(strict_types=1);


namespace App\Components\Router\ParseRequestBody;

use Psr\Http\Message\ServerRequestInterface;

interface RequestBodyParserInterface
{
    public function getParsedRequest(): ServerRequestInterface;
}
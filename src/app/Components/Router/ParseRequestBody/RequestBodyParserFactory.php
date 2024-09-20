<?php

declare(strict_types=1);

namespace App\Components\Router\ParseRequestBody;

use App\Components\Container\ServiceManagerInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestBodyParserFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): RequestBodyParserInterface
    {
        return new RequestBodyParser(
            $serviceManager->get(ServerRequestInterface::class),
        );
    }
}
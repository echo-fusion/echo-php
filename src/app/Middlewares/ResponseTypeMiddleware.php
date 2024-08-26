<?php

declare(strict_types=1);

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResponseTypeMiddleware implements MiddlewareInterface
{
    public const RESPONSE_TYPE = 'response_type';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute(self::RESPONSE_TYPE, $request->getHeader("Content-Type"));

        return $handler->handle($request);
    }
}

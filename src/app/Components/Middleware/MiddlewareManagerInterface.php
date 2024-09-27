<?php

declare(strict_types=1);

namespace App\Components\Middleware;

use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareManagerInterface
{
    /**
     * @param array<class-string> $middlewares
     */
    public function add(array $middlewares): void;

    public function remove(string $middlewareFQDN): void;

    public function dispatch(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;

    /**
     * @param class-string|non-empty-string $middlewareFQDN
     */
    public function isRouteMiddlewareValid(string $middlewareFQDN): bool;
}

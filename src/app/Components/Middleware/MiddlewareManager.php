<?php

declare(strict_types=1);

namespace App\Components\Middleware;

use App\Components\Container\ServiceManagerInterface;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\Pattern\MiddlewarePipeline;
use App\Middlewares\ResponseTypeMiddleware;
use App\Middlewares\StartSessionMiddleware;

final class MiddlewareManager implements MiddlewareManagerInterface
{
    public const CORE_MIDDLEWARES = [
        StartSessionMiddleware::class,
        ResponseTypeMiddleware::class,
    ];

    public const ROUTE_MIDDLEWARES = [
        AuthMiddleware::class,
        GuestMiddleware::class,
    ];

    public function __construct(private readonly ServiceManagerInterface $serviceManager)
    {
    }

    public function createPipeline(): MiddlewarePipeline
    {
        $pipeline = new MiddlewarePipeline();

        foreach (self::CORE_MIDDLEWARES as $middleware) {
            $pipeline->pipe($this->serviceManager->get($middleware));
        }

        return $pipeline;
    }

    public function isRouteMiddlewareValid(string $middlewareFQDN): bool
    {
        return in_array($middlewareFQDN, self::ROUTE_MIDDLEWARES, true);
    }
}

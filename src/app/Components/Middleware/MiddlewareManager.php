<?php

declare(strict_types=1);

namespace App\Components\Middleware;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Middleware\Auth\AuthMiddleware;
use App\Components\Middleware\Guest\GuestMiddleware;
use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;
use App\Components\Middleware\StartSession\StartSessionMiddleware;

final class MiddlewareManager implements MiddlewareManagerInterface
{
    public const CORE_MIDDLEWARES = [
        StartSessionMiddleware::class,
    ];

    public const ROUTE_MIDDLEWARES = [
        AuthMiddleware::class,
        GuestMiddleware::class,
    ];

    public function __construct(
        private readonly ServiceManagerInterface $serviceManager,
        private readonly MiddlewarePipelineInterface $middlewarePipeline
    ) {
    }

    public function createPipelineFromCoreMiddlewares(): MiddlewarePipelineInterface
    {
        foreach (self::CORE_MIDDLEWARES as $middleware) {
            $this->middlewarePipeline->pipe($this->serviceManager->get($middleware));
        }

        return $this->middlewarePipeline;
    }

    public function isRouteMiddlewareValid(string $middlewareFQDN): bool
    {
        return in_array($middlewareFQDN, self::ROUTE_MIDDLEWARES, true);
    }
}

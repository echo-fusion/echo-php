<?php

declare(strict_types=1);

namespace App;

use App\Contracts\MiddlewareFactoryInterface;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\Pattern\MiddlewarePipeline;
use App\Middlewares\StartSessionMiddleware;
use Psr\Container\ContainerInterface;

final class MiddlewareFactory implements MiddlewareFactoryInterface
{
    public const CORE_MIDDLEWARES = [
        StartSessionMiddleware::class,
    ];

    public const ROUTE_MIDDLEWARES = [
        AuthMiddleware::class,
        GuestMiddleware::class,
    ];

    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function createPipeline(): MiddlewarePipeline
    {
        $pipeline = new MiddlewarePipeline();

        $middlewares = [];
        foreach (self::CORE_MIDDLEWARES as $middleware) {
            $pipeline->pipe($this->container->get($middleware));
        }

        return $pipeline;
    }
}

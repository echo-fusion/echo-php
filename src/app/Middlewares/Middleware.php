<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Container;
use ReflectionException;
use App\Contracts\MiddlewareInterface;
use App\Exceptions\ContainerException;
use App\Exceptions\MiddlewareException;

class Middleware
{
    public const MAP = [
        'guest' => GuestMiddleware::class,
        'auth' => AuthMiddleware::class,
    ];

    /**
     * @param $key
     * @param Container $container
     * @return void
     * @throws MiddlewareException
     * @throws ContainerException
     * @throws ReflectionException
     */
    public static function resolve($key, Container $container): void
    {
        if (!$key) {
            return;
        }

        $middleware = static::MAP[$key] ?? false;

        if (!$middleware) {
            throw new MiddlewareException("Not matching middleware found for key '{$key}' . ");
        }

        /** @var MiddlewareInterface  $middlewareObj */
        $middlewareObj = $container->get($middleware);
        $middlewareObj->handle();
    }
}
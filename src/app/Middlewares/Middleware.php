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
    /**
     * @var array|string[]
     */
    public const Middleware = [
        'start_session' => StartSessionMiddleware::class,
    ];

    /**
     * @var array|string[]
     */
    public const RouteMiddleware = [
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
        // middlewares
        foreach (self::Middleware as $middleware) {
            self::callMiddleware($container, $middleware);
        }

        // route middleware
        if (!$key) {
            return;
        }

        $routeMiddleware = self::RouteMiddleware[$key] ?? false;
        if (!$routeMiddleware) {
            throw new MiddlewareException("Not matching middleware found for key '{$key}' . ");
        }

        self::callMiddleware($container, $routeMiddleware);

    }

    /**
     * @param Container $container
     * @param string $key
     * @return void
     * @throws ContainerException
     * @throws ReflectionException
     */
    private static function callMiddleware(Container $container, string $key)
    {
        /** @var MiddlewareInterface $middlewareObj */
        $middlewareObj = $container->get($key);
        $middlewareObj->handle();
    }
}
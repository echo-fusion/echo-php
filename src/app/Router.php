<?php

declare(strict_types=1);

namespace App;

use App\Contracts\RouterInterface;
use App\Exceptions\RouteNotFoundException;
use App\Middlewares\Pattern\MiddlewarePipeline;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router implements RouterInterface
{
    private array $routes = [];

    public function __construct(
        protected readonly ContainerInterface $container,
        protected readonly MiddlewarePipeline $middlewarePipeline,
    ) {
    }

    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[] = [
            'method' => $requestMethod,
            'route' => $route,
            'action' => $action,
            'middlewares' => clone $this->middlewarePipeline,
        ];

        return $this;
    }

    /**
     * @param list<string> $middlewares
     * @return Router
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function middlewares(string ...$middlewares): self
    {
        $lastIndex = array_key_last($this->routes);
        $lastRoute = $this->routes[$lastIndex];

        /**@var MiddlewarePipeline $routeMiddleware */
        $routeMiddlewarePipeline = $lastRoute['middlewares'];

        foreach ($middlewares as $middleware) {
            $routeMiddlewarePipeline->pipe(
                $this->container->get($middleware)
            );
        }

        $this->routes[$lastIndex]['middlewares'] = $routeMiddlewarePipeline;

        return $this;
    }

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register('post', $route, $action);
    }

    public function put(string $route, callable|array $action): self
    {
        return $this->register('put', $route, $action);
    }

    public function delete(string $route, callable|array $action): self
    {
        return $this->register('delete', $route, $action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function resolve(ServerRequestInterface $request): mixed
    {
        $requestRoute = $request->getUri()->getPath();
        $requestMethod = $request->getMethod();

        $action = null;
        foreach ($this->routes as $route) {
            if ($route['route'] == $requestRoute && $route['method'] == strtolower($requestMethod)) {
                // run middleware
                /** @var MiddlewarePipeline $routeMiddlewaresPipeline */
                $routeMiddlewaresPipeline = $route['middlewares'];
                if (!$routeMiddlewaresPipeline->isPipeLineEmpty()) {
                    $routeMiddlewaresPipeline->process(
                        $request,
                        $this->container->get(RequestHandlerInterface::class)
                    );
                }

                $action = $route['action'];
            }
        }

        if (is_null($action)) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            [$class, $method] = $action;
            if (class_exists($class)) {
                $class = $this->container->get($class);
                if (method_exists($class, $method)) {
                    return call_user_func([$class, $method], $request);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}
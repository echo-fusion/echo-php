<?php

declare(strict_types=1);

namespace App\Components\Router;

use App\Components\Container\ServiceManagerInterface;
use App\Middlewares\Pattern\MiddlewarePipeline;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\Assert;

class Router implements RouterInterface
{
    /**
     * @var Route
     */
    private array $routes = [];

    public function __construct(
        protected readonly ServiceManagerInterface $container,
        protected readonly MiddlewarePipeline $middlewarePipeline,
    ) {
    }

    public function register(HttpMethod $method, string $route, array $action): self
    {
        $this->routes[] = new Route(
            method: $method,
            route: $route,
            action: $action,
            middlewarePipeline: clone $this->middlewarePipeline,
        );

        return $this;
    }

    public function middlewares(string ...$middlewares): self
    {
        $lastIndex = array_key_last($this->routes);
        Assert::notNull($lastIndex);
        Assert::keyExists($this->routes, $lastIndex);
        $lastRoute = $this->routes[$lastIndex];

        $routeMiddlewarePipeline = $lastRoute->getMiddlewarePipeline();
        Assert::notNull($routeMiddlewarePipeline);
        foreach ($middlewares as $middleware) {
            $routeMiddlewarePipeline->pipe(
                $this->container->get($middleware)
            );
        }
        $lastRoute->setMiddlewarePipeline($routeMiddlewarePipeline);

        return $this;
    }

    public function get(string $route, array $action): self
    {
        return $this->register(HttpMethod::GET, $route, $action);
    }

    public function post(string $route, array $action): self
    {
        return $this->register(HttpMethod::POST, $route, $action);
    }

    public function put(string $route, array $action): self
    {
        return $this->register(HttpMethod::PUT, $route, $action);
    }

    public function patch(string $route, array $action): self
    {
        return $this->register(HttpMethod::PATCH, $route, $action);
    }

    public function delete(string $route, array $action): self
    {
        return $this->register(HttpMethod::DELETE, $route, $action);
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
            if ($route->getRoute() == $requestRoute && $route->getMethod() == strtolower($requestMethod)) {
                // run route middlewares
                $routeMiddlewaresPipeline = $route->getMiddlewarePipeline();
                Assert::notNull($routeMiddlewaresPipeline);

                if (!$routeMiddlewaresPipeline->isPipeLineEmpty()) {
                    $routeMiddlewaresPipeline->process(
                        $request,
                        $this->container->get(RequestHandlerInterface::class)
                    );
                }

                $action = $route->getAction();
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

<?php

declare(strict_types=1);

namespace App\Components\Router;

use App\Components\Middleware\MiddlewareManagerInterface;
use App\Components\Middleware\Pattern\MiddlewarePipeline;
use App\Components\Router\ParseRequestBody\RequestBodyParserInterface;
use Closure;
use SplQueue;
use Webmozart\Assert\Assert;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Components\Container\ServiceManagerInterface;
use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;

class Router implements RouterInterface
{
    /**
     * @var array<Route>
     */
    private array $routes = [];

    public function __construct(
        protected readonly ServiceManagerInterface $serviceManager,
        protected readonly MiddlewareManagerInterface $middlewareManager,
        protected readonly RequestBodyParserInterface $requestBodyParser,
    ) {
    }

    public function getRoute(string $name): ?Route
    {
        if (key_exists($name, $this->routes)) {
            return $this->routes[$name];
        }
        return null;
    }

    public function validateRoute(RouteInterface $route): RouteInterface
    {
        $existenceRoute = $this->getRoute($route->getName());
        if ($existenceRoute !== null && $existenceRoute->getMethod() == $route->getMethod()) {
            throw new DuplicateRouteException();
        }

        return $route;
    }

    private function register(
        HttpMethod $method,
        string $name,
        string $path,
        array|Closure $action,
        ?array $constraints = []
    ): self {
        $route = new Route($path);
        $route->setName($name);
        $route->setMethod($method);
        $route->setAction($action);
        $route->setConstraints($constraints);

        $this->routes[$name] = $this->validateRoute($route);

        return $this;
    }

    public function get(string $name, string $path, array|Closure $action, ?array $constraints = []): self
    {
        return $this->register(HttpMethod::GET, $name, $path, $action, $constraints);
    }

    public function post(string $name, string $path, array|Closure $action, ?array $constraints = []): self
    {
        return $this->register(HttpMethod::POST, $name, $path, $action, $constraints);
    }

    public function put(string $name, string $path, array|Closure $action, ?array $constraints = []): self
    {
        return $this->register(HttpMethod::PUT, $name, $path, $action, $constraints);
    }

    public function patch(string $name, string $path, array|Closure $action, ?array $constraints = []): self
    {
        return $this->register(HttpMethod::PATCH, $name, $path, $action, $constraints);
    }

    public function delete(string $name, string $path, array|Closure $action, ?array $constraints = []): self
    {
        return $this->register(HttpMethod::DELETE, $name, $path, $action, $constraints);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function middlewares(string ...$middlewares): self
    {
        $lastIndex = array_key_last($this->routes);
        Assert::notNull($lastIndex);
        Assert::keyExists($this->routes, $lastIndex);
        $lastRoute = $this->routes[$lastIndex];

        $routeMiddlewarePipeline = $lastRoute->getMiddlewarePipeline();
        if (!$routeMiddlewarePipeline instanceof MiddlewarePipelineInterface) {
            $routeMiddlewarePipeline = new MiddlewarePipeline();
        }

        foreach ($middlewares as $middleware) {
            $routeMiddlewarePipeline->pipe($this->serviceManager->get($middleware));
        }

        $lastRoute->setMiddlewarePipeline($routeMiddlewarePipeline);

        return $this;
    }

    private function dispatchMiddlewares(RouteInterface $route, ServerRequestInterface $request): void
    {
        // core middlewares
        $corePipeline = $this->middlewareManager->createPipelineFromCoreMiddlewares();
        $corePipeline->process($request, $this->serviceManager->get(RequestHandlerInterface::class));
        // route middlewares
        $routeMiddlewaresPipeline = $route->getMiddlewarePipeline();
        if ($routeMiddlewaresPipeline instanceof MiddlewarePipelineInterface) {
            $routeMiddlewaresPipeline->process($request, $this->serviceManager->get(RequestHandlerInterface::class));
        }
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws RouteNotFoundException
     * @throws ContainerExceptionInterface
     */
    public function dispatch(ServerRequestInterface $request): mixed
    {
        $request = $this->requestBodyParser->getParsedRequest();

        $action = null;
        $routeMatch = null;
        foreach ($this->routes as $route) {
            $routeMatch = $route->match($request);
            if ($routeMatch instanceof RouteMatchInterface) {
                $action = $route->getAction();
                $this->dispatchMiddlewares($route, $request);
                break;
            }
        }

        if (is_null($routeMatch)) {
            throw new RouteNotFoundException();
        }

        $request = $request->withAttribute(RouteMatchInterface::REQUEST_KEY, $routeMatch);

        if (is_callable($action)) {
            return call_user_func($action, $request);
        }

        Assert::isArray($action);
        [$class, $method] = $action;
        if (class_exists($class)) {
            $class = $this->serviceManager->get($class);
            if (method_exists($class, $method)) {
                return call_user_func([$class, $method], $request);
            }
        }

        throw new RouteNotFoundException();
    }
}

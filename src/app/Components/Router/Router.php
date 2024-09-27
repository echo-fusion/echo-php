<?php

declare(strict_types=1);

namespace App\Components\Router;

use Closure;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;
use App\Components\Middleware\MiddlewareManagerInterface;
use App\Components\Middleware\Pattern\MiddlewarePipeline;
use App\Components\Router\ParseRequestBody\RequestBodyParserInterface;
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

        foreach ($middlewares as $middleware) {
            if (!class_exists($middleware)) {
                throw new \Exception(sprintf('Middleware %s can not be added to route because it doesnt exist!',
                    $middleware));
            }
        }

        $lastRoute->setMiddlewarePipeline($middlewares);

        return $this;
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
                // add route middlewares
                $this->middlewareManager->add($route->getMiddlewares());
                break;
            }
        }

        if (is_null($routeMatch)) {
            throw new RouteNotFoundException();
        }

        $request = $request->withAttribute(RouteMatchInterface::REQUEST_KEY, $routeMatch);

        return $this->middlewareManager->dispatch(
            $request,
            $this->createActionHandler($action)
        );
    }

    private function createActionHandler($action): RequestHandlerInterface
    {
        return new class($action, $this->serviceManager) implements RequestHandlerInterface {
            public function __construct(private readonly string $action, private readonly ContainerInterface $container)
            {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                if (is_callable($this->action)) {
                    return call_user_func($this->action, $request);
                }

                [$class, $method] = $this->action;

                if (class_exists($class)) {
                    $controller = $this->container->get($class);

                    if (method_exists($controller, $method)) {
                        return call_user_func([$controller, $method], $request);
                    }
                }

                throw new RouteNotFoundException("Action not found.");
            }
        };
    }

}

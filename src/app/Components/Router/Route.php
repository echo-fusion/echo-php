<?php

declare(strict_types=1);

namespace App\Components\Router;

use Closure;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class Route implements RouteInterface
{
    protected HttpMethod $method;

    protected string $name;

    protected string $path;

    protected array|Closure $action;

    protected ?array $middlewares = [];

    protected ?array $constraints = [];

    protected array $arguments = [];

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function assemble(array $params = []): UriInterface
    {
        //@todo: bind route parameter with given data
        $uri = (new Uri())
            ->withHost()
            ->withPort()
            ->withPath()
            ->withScheme()
            ->withQuery();

        return $uri;
    }

    public function match(ServerRequestInterface $request): ?RouteMatchInterface {
        $path = $this->getPath();
        $requestMethod = $request->getMethod();
        $requestedPath = $request->getUri()->getPath();

        if (strtolower($this->getMethod()->value) !== strtolower($requestMethod)) {
            return null;
        }

        $constraints = $this->getConstraints() ?? [];

        $routeRegex = preg_replace_callback('/{([^}]+)}/', function ($matches) use ($constraints) {
            $paramName = $matches[1];
            return isset($constraints[$paramName]) ? '(' . $constraints[$paramName] . ')' : '([a-zA-Z0-9_-]+)';
        }, $path);

        $routeRegex = '@^' . $routeRegex . '$@';

        if (preg_match($routeRegex, $requestedPath, $matches)) {
            array_shift($matches);

            $routeParamsNames = [];
            if (preg_match_all('/{(\w+)(:[^}]+)?}/', $path, $paramMatches)) {
                $routeParamsNames = $paramMatches[1];
            }

            $routeParams = array_combine($routeParamsNames, $matches);

            return (new RouteMatch())
                ->setMatchedRouteName($this->getName())
                ->setBody((array)$request->getParsedBody())
                ->setParams($routeParams);
        }

        return null;
    }

    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    public function setMethod(HttpMethod $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAction(): array|Closure
    {
        return $this->action;
    }

    public function setAction(array|Closure $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function setMiddlewarePipeline(array $middlewares): self
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    public function getConstraints(): ?array
    {
        return $this->constraints;
    }

    public function setConstraints(?array $constraints): self
    {
        $this->constraints = $constraints;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

}

<?php

declare(strict_types=1);

namespace App\Components\Router;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Middleware\Pattern\MiddlewarePipeline;
use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;
use App\Components\Router\ParseRequestBody\RequestBodyParser;
use App\Components\Router\ParseRequestBody\RequestBodyParserInterface;
use Closure;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Webmozart\Assert\Assert;

class Route implements RouteInterface
{
    protected HttpMethod $method;

    protected string $name;

    protected string $path;

    protected array|Closure $action;

    protected ?MiddlewarePipeline $middlewarePipeline = null;

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


        // Check if the HTTP methods match
        if (strtolower($this->getMethod()->value) !== strtolower($requestMethod)) {
            return null;
        }

        // Get the constraints for the route
        $constraints = $this->getConstraints() ?? [];

        // Convert the route to a regular expression
        $routeRegex = preg_replace_callback('/{([^}]+)}/', function ($matches) use ($constraints) {
            $paramName = $matches[1];
            // If a constraint exists for the parameter, use it; otherwise, use a default pattern
            return isset($constraints[$paramName]) ? '(' . $constraints[$paramName] . ')' : '([a-zA-Z0-9_-]+)';
        }, $path);

        // Add start and end delimiters to the pattern
        $routeRegex = '@^' . $routeRegex . '$@';

        // Check if the requested path matches the generated route regex
        if (preg_match($routeRegex, $requestedPath, $matches)) {
            // Remove the first element which is the full match
            array_shift($matches);

            // Get route parameter names
            $routeParamsNames = [];
            if (preg_match_all('/{(\w+)(:[^}]+)?}/', $path, $paramMatches)) {
                $routeParamsNames = $paramMatches[1];
            }

            // Combine parameter names with the matched values
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

    public function getMiddlewarePipeline(): ?MiddlewarePipelineInterface
    {
        return $this->middlewarePipeline;
    }

    public function setMiddlewarePipeline(MiddlewarePipelineInterface $middlewarePipeline): self
    {
        $this->middlewarePipeline = $middlewarePipeline;
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

<?php

declare(strict_types=1);

namespace App\Components\Router;

use Closure;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;

interface RouteInterface
{
    public function match(ServerRequestInterface $request): ?RouteMatchInterface;

    public function assemble(array $params = []): UriInterface;

    public function getMethod(): HttpMethod;

    public function setMethod(HttpMethod $method): self;

    public function getName(): string;

    public function setName(string $name): self;

    public function getAction(): array|Closure;

    public function setAction(array|Closure $action): self;

    public function getMiddlewares(): array;

    public function setMiddlewarePipeline(array $middlewares): self;

    public function getConstraints(): ?array;

    public function setConstraints(?array $constraints): self;

    public function getPath(): string;
}













<?php

declare(strict_types=1);

namespace App\Components\Router;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @param non-empty-string $route
     * @param list<non-empty-string> $action
     */
    public function register(HttpMethod $method, string $route, array $action): self;

    /**
     * @param non-empty-string $route
     * @param list<non-empty-string> $action
     */
    public function get(string $route, array $action): self;

    /**
     * @param non-empty-string $route
     * @param list<non-empty-string> $action
     */
    public function post(string $route, array $action): self;

    /**
     * @param non-empty-string $route
     * @param list<non-empty-string> $action
     */
    public function put(string $route, array $action): self;

    /**
     * @param non-empty-string $route
     * @param list<non-empty-string> $action
     */
    public function delete(string $route, array $action): self;

    /**
     * @return Route
     */
    public function routes(): array;

    public function resolve(ServerRequestInterface $request): mixed;

    /**
     * @param non-empty-string ...$middlewares
     */
    public function middlewares(string ...$middlewares): self;
}

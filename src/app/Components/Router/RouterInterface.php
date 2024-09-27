<?php

declare(strict_types=1);

namespace App\Components\Router;

use Closure;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @param non-empty-string $name
     */
    public function getRoute(string $name): ?Route;

    public function validateRoute(RouteInterface $route): RouteInterface;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $path
     * @param list<non-empty-string>|Closure $action
     * @param array<array-key,non-empty-string>|null $constraints
     */
    public function get(string $name, string $path, array|Closure $action, ?array $constraints = []): self;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $path
     * @param list<non-empty-string>|Closure $action
     * @param array<array-key,non-empty-string>|null $constraints
     */
    public function post(string $name, string $path, array|Closure $action, ?array $constraints = []): self;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $path
     * @param list<non-empty-string>|Closure $action
     * @param array<array-key,non-empty-string>|null $constraints
     */
    public function put(string $name, string $path, array|Closure $action, ?array $constraints = []): self;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $path
     * @param list<non-empty-string>|Closure $action
     * @param array<array-key,non-empty-string>|null $constraints
     */
    public function patch(string $name, string $path, array|Closure $action, ?array $constraints = []): self;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $path
     * @param list<non-empty-string>|Closure $action
     * @param array<array-key,non-empty-string>|null $constraints
     */
    public function delete(string $name, string $path, array|Closure $action, ?array $constraints = []): self;

    /**
     * @return array<Route>
     */
    public function routes(): array;

    public function dispatch(ServerRequestInterface $request): mixed;

    /**
     * @param non-empty-string ...$middlewares
     */
    public function middlewares(string ...$middlewares): self;
}

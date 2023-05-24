<?php

declare(strict_types=1);

namespace App\Contracts;

interface RouterInterface
{
    public function register(string $requestMethod, string $route, callable|array $action): self;

    public function get(string $route, callable|array $action): self;

    public function post(string $route, callable|array $action): self;

    public function put(string $route, callable|array $action): self;

    public function delete(string $route, callable|array $action): self;

    public function routes(): array;

    public function resolve(string $requestUri, string $requestMethod);

    public function only($key): self;
}

<?php

declare(strict_types=1);

namespace App\Components\Router;

use App\Middlewares\Pattern\MiddlewarePipeline;

class Route
{
    /**
     * @param HttpMethod $method
     * @param non-empty-string $route
     * @param list<non-empty-string> $action
     * @param MiddlewarePipeline $middlewarePipeline
     */
    public function __construct(
        protected HttpMethod $method,
        protected string $route,
        protected array $action,
        protected MiddlewarePipeline $middlewarePipeline,
    ) {
    }

    public function getMethod(): string
    {
        return strtolower($this->method->value);
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return list<non-empty-string>
     */
    public function getAction(): array
    {
        return $this->action;
    }

    public function getMiddlewarePipeline(): ?MiddlewarePipeline
    {
        return $this->middlewarePipeline;
    }

    public function setMiddlewarePipeline(MiddlewarePipeline $middlewarePipeline): void
    {
        $this->middlewarePipeline = $middlewarePipeline;
    }
}

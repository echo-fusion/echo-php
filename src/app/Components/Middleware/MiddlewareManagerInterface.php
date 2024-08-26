<?php

declare(strict_types=1);

namespace App\Components\Middleware;

use App\Middlewares\Pattern\MiddlewarePipeline;

interface MiddlewareManagerInterface
{
    public function createPipeline(): MiddlewarePipeline;

    public function isRouteMiddlewareValid(string $middlewareFQDN): bool;
}

<?php

declare(strict_types=1);

namespace App\Components\Middleware;

use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;

interface MiddlewareManagerInterface
{
    public function createPipelineFromCoreMiddlewares(): MiddlewarePipelineInterface;

    public function isRouteMiddlewareValid(string $middlewareFQDN): bool;
}

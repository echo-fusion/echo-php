<?php

declare(strict_types=1);

namespace App\Components\Middleware\Pattern;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewarePipelineInterface
{
    public function isPipeLineEmpty(): bool;

    public function pipe(MiddlewareInterface $middleware): void;
}
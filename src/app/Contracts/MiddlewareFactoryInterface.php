<?php

namespace App\Contracts;

use App\Middlewares\Pattern\MiddlewarePipeline;
use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareFactoryInterface
{
    public function createPipeline(): MiddlewarePipeline;
}
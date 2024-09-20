<?php

declare(strict_types=1);

namespace App\Components\Middleware;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;
use Webmozart\Assert\Assert;

class MiddlewareManagerFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): MiddlewareManagerInterface
    {
        $middlewarePipeline = $serviceManager->get(MiddlewarePipelineInterface::class);
        Assert::isInstanceOf($middlewarePipeline, MiddlewarePipelineInterface::class);

        return new MiddlewareManager($serviceManager, $middlewarePipeline);
    }
}

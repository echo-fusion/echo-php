<?php

declare(strict_types=1);

namespace App\Components\Middleware;

use App\Components\Container\ServiceManagerInterface;

class MiddlewareManagerFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): MiddlewareManagerInterface
    {
        return new MiddlewareManager($serviceManager);
    }

    public function asdadff(){

    }
}

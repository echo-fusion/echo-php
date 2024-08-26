<?php

declare(strict_types=1);

namespace App\Components\Container\Strategies;

use App\Components\Container\DependenciesRepositoryInterface;
use App\Components\Container\ServiceManagerInterface;

interface ContainerResolverStrategyInterface
{
    public function resolve(string $id, ServiceManagerInterface $serviceManager): mixed;
}

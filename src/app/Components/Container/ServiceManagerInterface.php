<?php

declare(strict_types=1);

namespace App\Components\Container;

use Psr\Container\ContainerInterface;

interface ServiceManagerInterface extends ContainerInterface
{
    public function bind(DependenciesRepositoryInterface $dependenciesRepository): void;

    /**
     * @param non-empty-string $id
     */
    public function resolve(string $id): mixed;

    public function getDependenciesManager(): DependenciesRepositoryInterface;
}

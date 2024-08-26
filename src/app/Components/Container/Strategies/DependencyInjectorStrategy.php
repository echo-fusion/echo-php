<?php

declare(strict_types=1);

namespace App\Components\Container\Strategies;

use App\Components\Container\ContainerException;
use App\Components\Container\DependenciesRepositoryInterface;
use App\Components\Container\ServiceManagerInterface;

class DependencyInjectorStrategy implements ContainerResolverStrategyInterface
{
    public function resolve(string $id, ServiceManagerInterface $serviceManager): mixed
    {
        $dependenciesRepository = $serviceManager->getDependenciesManager();
        $dependencyType = $dependenciesRepository->getType($id);

        $result = match ($dependencyType) {
            DependenciesRepositoryInterface::Alias => $this->getAliasInstance($id, $serviceManager),
            DependenciesRepositoryInterface::Invokable => $this->getInvokableInstance($id, $serviceManager),
            DependenciesRepositoryInterface::Factory => $this->getFactoryInstance($id, $serviceManager),
        };

        if (!is_object($result)) {
            throw new ContainerException(sprintf('Dependency can not be resolved for "%s"!', $id));
        }

        return $result;
    }

    public function getAliasInstance(string $id, ServiceManagerInterface $serviceManager): mixed
    {
        $dependenciesRepository = $serviceManager->getDependenciesManager();
        $instance = $dependenciesRepository->get($id);

        return $this->resolve($instance, $serviceManager);
    }

    public function getInvokableInstance(string $id, ServiceManagerInterface $serviceManager): object
    {
        $dependenciesRepository = $serviceManager->getDependenciesManager();
        $instance = $dependenciesRepository->get($id);

        return new $instance();
    }

    public function getFactoryInstance(string $id, ServiceManagerInterface $serviceManager): object
    {
        $dependenciesRepository = $serviceManager->getDependenciesManager();
        $class = new ($dependenciesRepository->get($id));

        return $class($serviceManager);
    }
}

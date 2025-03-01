<?php

declare(strict_types=1);

namespace App\Components\Container\Strategies;

use App\Components\Container\ContainerException;
use App\Components\Container\ServiceManagerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionUnionType;

class AutoWiringStrategy implements ContainerResolverStrategyInterface
{
    public function resolve(string $id, ServiceManagerInterface $serviceManager): mixed
    {
        $dependenciesRepository = $serviceManager->getDependenciesManager();
        $entry = $dependenciesRepository->get($id);

        $reflectionClass = new ReflectionClass($entry);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException(sprintf('Class "%s" is not instantiable!', $entry));
        }

        $invokable = false;
        foreach ($reflectionClass->getMethods() as $methodObj) {
            if ($methodObj->getname() == '__invoke') {
                $invokable = true;
            }
        }
        if ($invokable) {
            return (new $entry)($serviceManager);
        }

        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $entry();
        }
        $parameters = $constructor->getParameters();
        if (!$parameters) {
            return new $entry();
        }

        $dependencies = array_map(function (\ReflectionParameter $param) use ($entry, $serviceManager) {
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new ContainerException(
                    'Failed to resolve the class ' . $entry . ' because param ' . $name . ' is missing a type hint'
                );
            }

            if ($type instanceof ReflectionUnionType) {
                throw new ContainerException(
                    'Failed to resolve the class ' . $entry . ' because of union type of param ' . $name
                );
            }

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $entry = $serviceManager->getDependenciesManager()->get($type->getName());
                return $this->resolve($entry, $serviceManager);
            }

            throw new ContainerException('Failed to resolve the class ' . $entry . ' because invalid params!');
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}

<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;
use ReflectionException;

class Container implements ContainerInterface
{
    private array $entries = [];

    /**
     * @param string $id
     * @return mixed|object|string|null
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];
            if (is_callable($entry)) {
                // call closure with passing container object
                return $entry($this);
            }
            // if pass param as an interface instead of class [string]
            $id = $entry;
        }
        // auto wiring
        return $this->resolve($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    /**
     * @param string $id
     * @param callable|string $concrete
     * @return void
     */
    public function bind(string $id, callable|string $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    /**
     * @param string $id
     * @return mixed|object|string|null
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function resolve(string $id): mixed
    {
        $reflectionClass = new \ReflectionClass($id);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('Class ' . $id . ' is not instantiable!');
        }

        $constructor = $reflectionClass->getConstructor();
        // without any dependencies
        if (!$constructor) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        if (!$parameters) {
            return new $id();
        }

        // resolve params which are classes
        $dependencies = array_map(function (\ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new ContainerException(
                    'Failed to resolve the class ' . $id . ' because param ' . $name . ' is missing a type hint'
                );
            }

            if ($type instanceof \ReflectionUnionType) {
                throw new ContainerException(
                    'Failed to resolve the class ' . $id . ' because of union type of param ' . $name
                );
            }

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException('Failed to resolve the class ' . $id . ' because invalid params!');
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}

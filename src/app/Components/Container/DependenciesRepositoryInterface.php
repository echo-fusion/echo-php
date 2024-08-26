<?php

declare(strict_types=1);

namespace App\Components\Container;

interface DependenciesRepositoryInterface
{
    public const Factory = 'factory';
    public const Invokable = 'invokable';
    public const Alias = 'alias';

    public function get(string $id);

    public function has(string $id): bool;

    public function getType(string $id);

    public function getDependencies(): array;

    public function getFactories(): array;

    public function getInvokables(): array;

    public function getAliases(): array;

    public function setFactory(string $id, callable|string $concrete): void;

    public function setInvokable(string $id): void;

    public function setAlias(string $id, string $concrete): void;
}

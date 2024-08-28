<?php

declare(strict_types=1);

namespace App\Components\Container;

use App\Components\Container\Strategies\ContainerResolverStrategyInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ServiceManager implements ServiceManagerInterface
{
    public function __construct(
        private DependenciesRepositoryInterface $dependenciesRepository,
        private readonly ContainerResolverStrategyInterface $containerResolverStrategy,
        private readonly bool $allowOverride,
        private readonly AdapterInterface $cacheAdapter
    ) {
        $this->bind($dependenciesRepository);
    }

    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            $entry = $this->dependenciesRepository->get($id);
            if (is_callable($entry)) {
                return ($entry($this));
            }
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return $this->dependenciesRepository->has($id);
    }

    public function getDependenciesManager(): DependenciesRepositoryInterface
    {
        return $this->dependenciesRepository;
    }

    public function bind(DependenciesRepositoryInterface $dependenciesRepository): void
    {
        $this->dependenciesRepository = $dependenciesRepository;

        foreach ($dependenciesRepository->getDependencies() as $id => $entry) {
            if ($this->has($id) && !$this->allowOverride) {
                throw new ContainerException(sprintf('Dependency "%s" is already exist in container!', $id));
            }

            match ($dependenciesRepository->getType($id)) {
                DependenciesRepositoryInterface::Alias => $this->dependenciesRepository->setAlias($id, $entry),
                DependenciesRepositoryInterface::Invokable => $this->dependenciesRepository->setInvokable($entry),
                DependenciesRepositoryInterface::Factory => $this->dependenciesRepository->setFactory($id, $entry),
            };
        }
    }

    public function resolve(string $id): mixed
    {
        $key = $this->convertSlashToDash($id);

        return $this->cacheAdapter->get($key, function (ItemInterface $item) use ($id) {
            return $this->containerResolverStrategy->resolve($id, $this);
        });
    }

    private function convertSlashToDash(string $string): string
    {
        return preg_replace('/[\W\s\/]+/', '-', $string);
    }
}

<?php

declare(strict_types=1);

use App\Application;
use App\Components\Config\ConfigInterface;
use App\Components\Container\DependenciesRepository;
use App\Components\Container\DependenciesRepositoryInterface;
use App\Components\Container\ServiceManager;
use App\Components\Container\Strategies\ContainerResolverStrategyInterface;
use Dotenv\Dotenv;
use Webmozart\Assert\Assert;

require __DIR__ . '/config/path_constants.php';
require ROOT_PATH . '/vendor/autoload.php';

// load environment file
$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// load dependencies
$dependenciesRepository = new DependenciesRepository();
$containers = require CONFIG_PATH . '/container.php';
$containers($dependenciesRepository);

$dependenciesRepository->setFactory(DependenciesRepositoryInterface::class, function () use ($dependenciesRepository) {
    return $dependenciesRepository;
});

// load configs
/** @var ConfigInterface $applicationConfig */
$applicationConfig = require CONFIG_PATH . '/application.config.php';
Assert::isInstanceOf($applicationConfig, ConfigInterface::class);
$config = $applicationConfig->getMerged();

$dependenciesRepository->setFactory(ConfigInterface::class, function () use ($applicationConfig) {
    return $applicationConfig;
});

// initial service manager & application
Assert::keyExists($config, 'service_manager');
Assert::keyExists($config['service_manager'], 'resolver');
Assert::keyExists($config['service_manager'], 'allow_override');
$allowOverride = (bool)$config['service_manager']['allow_override'];

$resolver = $config['service_manager']['resolver'];
$resolver = new $resolver;
Assert::isInstanceOf($resolver, ContainerResolverStrategyInterface::class);

// choosing a cache adapter: for running service manager without cache, just use "NullAdapter"
Assert::keyExists($config['service_manager'], 'cache_adapter');
$cacheAdapterClass = $config['service_manager']['cache_adapter'];

$cacheAdapter = new \Symfony\Component\Cache\Adapter\NullAdapter();
/**
 $cacheAdapter = new $cacheAdapterClass(
    namespace: 'service-manager',
    defaultLifetime: 0,
    directory: STORAGE_PATH
);
 */

return new Application(
    new ServiceManager(
        $dependenciesRepository,
        $resolver,
        $allowOverride,
        $cacheAdapter
    )
);
<?php

declare(strict_types=1);

use App\Application;
use App\Components\Config\ConfigInterface;
use App\Components\Container\DependenciesRepository;
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

// load configs
$applicationConfig = require CONFIG_PATH . '/application.config.php';
Assert::isInstanceOf($applicationConfig, ConfigInterface::class);
$dependenciesRepository->setFactory(ConfigInterface::class, function () use ($applicationConfig) {
    return $applicationConfig;
});

// initial service manager & application
$config = $applicationConfig->getMerged();
Assert::keyExists($config, 'service_manager');
Assert::keyExists($config['service_manager'], 'resolver');
Assert::keyExists($config['service_manager'], 'allow_override');
$allowOverride = (bool)$config['service_manager']['allow_override'];

$resolver = $config['service_manager']['resolver'];
$resolver = new $resolver;
Assert::isInstanceOf($resolver, ContainerResolverStrategyInterface::class);

return new Application(
    new ServiceManager(
        $dependenciesRepository,
        $resolver,
        $allowOverride
    )
);
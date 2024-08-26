<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\Config\ConfigInterface;
use App\Components\Container\ServiceManagerInterface;
use App\Components\Environment\AppEnvironment;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Webmozart\Assert\Assert;

class TwigEnvironmentFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): Environment
    {
        $config = $serviceManager->get(ConfigInterface::class);
        Assert::isInstanceOf($config, ConfigInterface::class);

        $configArray = $config->getMerged();
        Assert::keyExists($configArray, 'environment');

        $environment = $configArray['environment'];
        $loader = new FilesystemLoader(VIEW_PATH);
        return new Environment($loader, [
            'cache' => STORAGE_PATH . '/cache/templates',
            'auto_reload' => $environment === AppEnvironment::Development->value,
        ]);
    }
}

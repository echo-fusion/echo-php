<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\Config\ConfigInterface;
use App\Components\Container\ServiceManagerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Webmozart\Assert\Assert;

class ConnectionFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): Connection
    {
        $config = $serviceManager->get(ConfigInterface::class);
        Assert::isInstanceOf($config, ConfigInterface::class);

        $configArray = $config->getMerged();
        Assert::keyExists($configArray, 'database_info');

        return DriverManager::getConnection($configArray['database_info']);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\DB;

use App\Components\Config\ConfigInterface;
use App\Components\Container\ServiceManagerInterface;
use Webmozart\Assert\Assert;

class DBFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): DB
    {
        $config = $serviceManager->get(ConfigInterface::class);
        Assert::isInstanceOf($config, ConfigInterface::class);
        $configArray = $config->getMerged();
        Assert::keyExists($configArray, 'database_info');

        return new DB($configArray['database_info']);
    }
}

<?php

declare(strict_types=1);

use App\Application;
use App\Components\Config\Config;
use App\Components\Config\ConfigInterface;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;
use Webmozart\Assert\Assert;

/** @var Application $application * */
$application = require __DIR__ . '/bootstrap.php';

/** @var Config $config */
$config = $application->getServiceManager()->get(ConfigInterface::class);

$configArray = $config->getMerged();
Assert::keyExists($configArray, 'migrations');
$migrationsConfig = $configArray['migrations'];

/** @var EntityManagerInterface $entityManager */
$entityManager = $application->getServiceManager()->get(EntityManagerInterface::class);
Assert::isInstanceOf($entityManager, EntityManagerInterface::class);

return DependencyFactory::fromEntityManager(
    new ConfigurationArray($migrationsConfig),
    new ExistingEntityManager($entityManager)
);
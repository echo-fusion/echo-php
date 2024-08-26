<?php

declare(strict_types=1);

use App\Application;
use App\Components\Config\Config;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;

/** @var Application $app * */
$app = require __DIR__ . '/bootstrap.php';

/** @var Config $config */
$config = $app->getContainer()->get(Config::class)->getMerged();
$migrationsConfig = $config['migrations'];

/** @var EntityManagerInterface $entityManager */
$entityManager = $app->getContainer()->get(EntityManagerInterface::class);

return DependencyFactory::fromEntityManager(
    new ConfigurationArray($migrationsConfig),
    new ExistingEntityManager($entityManager)
);
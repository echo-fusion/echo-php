<?php

declare(strict_types=1);

use App\App;
use App\Config;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;

/** @var App $app * */
$app = require __DIR__ . '/bootstrap.php';

/** @var Config $config */
$config = $app->getContainer()->get(Config::class);
$migrationsConfig = $config['migrations'];

/** @var EntityManagerInterface $entityManager */
$entityManager = $app->getContainer()->get(EntityManagerInterface::class);

return DependencyFactory::fromEntityManager(
    new ConfigurationArray($migrationsConfig),
    new ExistingEntityManager($entityManager)
);
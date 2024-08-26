<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\Container\ServiceManagerInterface;
use App\Components\DB\DB;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Webmozart\Assert\Assert;

class EntityManagerFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): EntityManagerInterface
    {
        $db = $serviceManager->get(DB::class);
        Assert::isInstanceOf($db, DB::class);

        return new EntityManager(
            $db->connection,
            ORMSetup::createAttributeMetadataConfiguration([APP_PATH . '/Entities'])
        );
    }
}

<?php

use App\Components\Container\Strategies\AutoWiringStrategy;
use App\Components\Container\Strategies\DependencyInjectorStrategy;
use App\Components\Response\Html\Handlers\Twig;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return [
    'service_manager' => [
        'allow_override' => true,
        'resolver' => DependencyInjectorStrategy::class,
        'cache_adapter' => FilesystemAdapter::class
    ],
    'cache' => [
        'adapter' => ArrayAdapter::class
    ],
    'response' =>[
        'html'=> [
            'agent' => Twig::class
        ]
    ],
    'database_info' => [
        'host' => $_ENV['DB_HOST'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
        'dbname' => $_ENV['DB_DATABASE'],
        'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
        "engine" => "innodb",
        "charset" => "utf8mb4",
        "collation" => "utf8mb4",
        "collate" => "utf8mb4_unicode_ci"
    ],
    'migrations' => [
        'table_storage' => [
            'table_name' => 'doctrine_migration_versions',
            'version_column_name' => 'version',
            'version_column_length' => 192,
            'executed_at_column_name' => 'executed_at',
            'execution_time_column_name' => 'execution_time',
        ],
        'migrations_paths' => [
            'Migrations' => MIGRATION_PATH,
        ],
        'all_or_nothing' => true,
        'transactional' => true,
        'check_database_platform' => true,
        'organize_migrations' => 'none',
        'connection' => null,
        'em' => null,
    ],
];

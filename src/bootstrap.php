<?php

declare(strict_types=1);

use App\App;
use App\Config;
use App\Container;
use Dotenv\Dotenv;

require __DIR__ . '/config/path_constants.php';
require ROOT_PATH . '/vendor/autoload.php';

// load environment file
$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$container = new Container();
$containers = require CONFIG_PATH . '/container.php';
$containers($container);

/** @var Config $config */
$config = require CONFIG_PATH . '/application.config.php';

return new App(
    $container,
    $config
);

<?php

declare(strict_types=1);

use App\App;
use App\Container;
use App\Router;

require __DIR__ . '/../config/path_constants.php';
require ROOT_PATH . '/vendor/autoload.php';

$container = new Container();

$router = new Router($container);
$routes = require CONFIG_PATH . '/routes.php';
$routes($router);

$app = new App($container);
$app->setRouter($router);
$app->setRequest(['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']]);
$app->run();
<?php

declare(strict_types=1);

use App\Container;
use App\Router;

require __DIR__ . '/../config/path_constants.php';
require ROOT_PATH . '/vendor/autoload.php';

$container = new Container();

$router = new Router($container);
$routes = require CONFIG_PATH . '/routes.php';
$routes($router);

// resolve router
echo $router->resolve($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD']));
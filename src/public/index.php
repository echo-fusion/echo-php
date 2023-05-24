<?php

declare(strict_types=1);

use App\Container;
use App\Router;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$router = new Router($container);


// resolve router
echo $router->resolve($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD']));
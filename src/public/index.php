<?php

declare(strict_types=1);

use App\App;
use App\Router;

/** @var App $app **/
$app = require __DIR__ . '/../bootstrap.php';
$container = $app->getContainer();

$router = new Router($container);
$routes = require CONFIG_PATH . '/routes.php';
$routes($router);

$app = new App($container);
$app->setRouter($router);
$app->setRequest(['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']]);
$app->run();
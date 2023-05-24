<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$router = new App\Router();

// define routes
$router->get('/test', function () {
    echo  'This is test route!';
});

// resolve router
echo $router->resolve($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD']));
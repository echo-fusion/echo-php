<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Router;

return function (Router $router) {
    // auth
    $router->get('/', [HomeController::class, 'home']);
    $router->get('/login', [AuthController::class, 'loginPage'])->middlewares(GuestMiddleware::class);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/register', [AuthController::class, 'registerPage'])->middlewares(GuestMiddleware::class);
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/logout', [AuthController::class, 'logout'])->middlewares(AuthMiddleware::class);
    // adding new routes here...

};
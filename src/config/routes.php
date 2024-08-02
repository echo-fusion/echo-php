<?php

declare(strict_types=1);

use App\View;
use App\Router;
use App\Controllers\AuthController;

return function (Router $router) {
    // auth
    $router->get('/login', [AuthController::class, 'loginPage'])->only('guest');
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/register', [AuthController::class, 'registerPage'])->only('guest');
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/logout', [AuthController::class, 'logout'])->only('auth');
    // closure routeing without rendering with twig
    $router->get('/imprint', function () {
        return View::make('imprint');
    });
};
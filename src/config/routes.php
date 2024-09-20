<?php

declare(strict_types=1);

use App\Components\Middleware\Auth\AuthMiddleware;
use App\Components\Middleware\Guest\GuestMiddleware;
use App\Components\Router\Router;
use App\Controllers\ApiController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;

return function (Router $router) {
    // test
    $router->get(
        name: 'test-get',
        path: '/post/{id}/detail/{slug}',
        action: fn(\Psr\Http\Message\ServerRequestInterface $request) => var_dump($request->getAttributes()),
        constraints: [
            'id' => '[0-9]+',
            'slug' => '[a-z0-9\-]+',
        ]
    );

    $router->post(
        name: 'test-post',
        path: '/post/{id}',
        action: fn(\Psr\Http\Message\ServerRequestInterface $request) => var_dump($request->getAttributes()),
        constraints: [
            'id' => '[0-9]+',
        ]
    );

    $router->get(name: 'home', path: '/', action: [HomeController::class, 'home']);
    $router->get(name: 'api.index', path: '/api', action: [ApiController::class, 'index']);

    // auth
    $router->get(name: 'login.page', path: '/login', action: [AuthController::class, 'loginPage'])
        ->middlewares(GuestMiddleware::class);
    $router->post(name: 'login', path: '/login', action: [AuthController::class, 'login'])
        ->middlewares(GuestMiddleware::class);
    $router->get(name: 'register.page', path: '/register', action: [AuthController::class, 'registerPage'])
        ->middlewares(GuestMiddleware::class);
    $router->post(name: 'register', path: '/register', action: [AuthController::class, 'register'])
        ->middlewares(GuestMiddleware::class);
    $router->post(name: 'logout', path: '/logout', action: [AuthController::class, 'logout'])
        ->middlewares(AuthMiddleware::class);
};

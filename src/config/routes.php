<?php

declare(strict_types=1);

use App\Controllers\CommentController;
use App\Router;
use App\Controllers\AuthController;
use App\Controllers\BlogController;

return function (Router $router) {
    // blog
    $router->get('/', [BlogController::class, 'index']);
    $router->get('/blog', [BlogController::class, 'show']);
    $router->get('/blog/create', [BlogController::class, 'create'])->only('auth');
    $router->post('/blog', [BlogController::class, 'store']);
    // auth
    $router->get('/login', [AuthController::class, 'loginPage'])->only('guest');
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/register', [AuthController::class, 'registerPage'])->only('guest');
    $router->post('/register', [AuthController::class, 'register']);
    $router->post('/logout', [AuthController::class, 'logout'])->only('auth');
    // comment
    $router->post('/comment', [CommentController::class, 'store'])->only('auth');
    $router->delete('/comment', [BlogController::class, 'delete'])->only('auth');

    // closure routeing without rendering with twig
    $router->get('/imprint', function () {
        echo 'imprint page';
    });
};
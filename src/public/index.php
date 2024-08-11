<?php

declare(strict_types=1);

use App\App;
use App\Contracts\MiddlewareFactoryInterface;
use App\Router;
use Psr\Http\Message\ServerRequestInterface;

(function () {
    /** @var App $application * */
    $application = require __DIR__ . '/../bootstrap.php';
    $container = $application->getContainer();

    /** @var MiddlewareFactoryInterface $middlewareFactory * */
    $middlewareFactory = $container->get(MiddlewareFactoryInterface::class);
    $middlewarePipeline = $middlewareFactory->createPipeline();

    $router = new Router($container, $middlewarePipeline);
    $routes = require CONFIG_PATH . '/routes.php';
    $routes($router);

    $application->setRouter($router);
    $application->setRequest($container->get(ServerRequestInterface::class));

    try {
        $application->run();
        exit(0);
    } catch (Throwable $exception) {
        //
    }
})();

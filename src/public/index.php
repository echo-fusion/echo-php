<?php

declare(strict_types=1);

use App\Application;
use App\Components\Container\ServiceManagerInterface;
use App\Components\Middleware\MiddlewareManagerInterface;
use App\Components\Router\Router;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

(function () {
    /** @var Application $application */
    $application = require __DIR__ . '/../bootstrap.php';
    $serviceManager = $application->getServiceManager();

    $middlewareManager = $serviceManager->get(MiddlewareManagerInterface::class);
    Assert::isInstanceOf($middlewareManager, MiddlewareManagerInterface::class);
    $middlewarePipeline = $middlewareManager->createPipeline();

    $router = new Router($serviceManager, $middlewarePipeline);
    $routes = require CONFIG_PATH . '/routes.php';
    $routes($router);
    $application->setRouter($router);

    $request = $serviceManager->get(ServerRequestInterface::class);
    Assert::isInstanceOf($request, ServerRequestInterface::class);
    $application->setRequest($serviceManager->get(ServerRequestInterface::class));

    try {
        $application->run();
        exit(0);
    } catch (Throwable $exception) {
        throw new Exception($exception->getMessage(), $exception->getCode());
    }
})();

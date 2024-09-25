<?php

declare(strict_types=1);

use App\Application;
use App\Components\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

(function () {

    /** @var Application $application */
    $application = require __DIR__ . '/../bootstrap.php';
    $serviceManager = $application->getServiceManager();

    $router = $serviceManager->get(RouterInterface::class);
    Assert::isInstanceOf($router, RouterInterface::class);
    $application->setRouter($router);

    $request = $serviceManager->get(ServerRequestInterface::class);
    Assert::isInstanceOf($request, ServerRequestInterface::class);
    $application->setRequest($request);

    try {
        $application->run();
        exit(0);
    } catch (Throwable $exception) {
        throw new Exception($exception->getMessage(), $exception->getCode());
    }
})();

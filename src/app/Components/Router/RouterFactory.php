<?php

declare(strict_types=1);

namespace App\Components\Router;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Middleware\MiddlewareManagerInterface;
use App\Components\Router\ParseRequestBody\RequestBodyParserInterface;
use Webmozart\Assert\Assert;

class RouterFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): RouterInterface
    {
        $middlewareManager = $serviceManager->get(MiddlewareManagerInterface::class);
        Assert::isInstanceOf($middlewareManager, MiddlewareManagerInterface::class);

        $requestBodyParser = $serviceManager->get(RequestBodyParserInterface::class);
        Assert::isInstanceOf($requestBodyParser, RequestBodyParserInterface::class);

        $router =  new Router($serviceManager, $middlewareManager, $requestBodyParser);

        $routes = require CONFIG_PATH . '/routes.php';
        $routes($router);

        return $router;
    }
}
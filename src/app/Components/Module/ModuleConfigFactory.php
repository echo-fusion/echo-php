<?php

namespace App\Components\Module;

use App\Components\Container\DependenciesRepositoryInterface;
use App\Components\Container\ServiceManagerInterface;
use App\Components\Middleware\MiddlewareManagerInterface;
use App\Components\Request\ParseRequestBody\RequestBodyParserInterface;
use App\Components\Router\Router;
use App\Components\Template\TemplateBuilderInterface;
use Modules\Blog\Config;
use Webmozart\Assert\Assert;

class ModuleConfigFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): ModuleConfigInterface
    {
        $requestBodyParser = $serviceManager->get(RequestBodyParserInterface::class);
        Assert::isInstanceOf($requestBodyParser, RequestBodyParserInterface::class);

        $middlewareManager = $serviceManager->get(MiddlewareManagerInterface::class);
        Assert::isInstanceOf($middlewareManager, MiddlewareManagerInterface::class);

        // create an empty router
        $router = new Router($serviceManager, $middlewareManager, $requestBodyParser);

        // create an empty dependency repository
        $dependenciesRepository = $serviceManager->get(DependenciesRepositoryInterface::class);
        Assert::isInstanceOf($dependenciesRepository, DependenciesRepositoryInterface::class);

        // create an empty template builder
        $templateBuilder = $serviceManager->get(TemplateBuilderInterface::class);
        Assert::isInstanceOf($templateBuilder, TemplateBuilderInterface::class);

        return new Config(
            $router,
            $dependenciesRepository,
            $templateBuilder
        );
    }
}
<?php

declare(strict_types=1);

use App\Components\Container\DependenciesRepositoryInterface;
use App\Components\DB\DB;
use App\Components\DB\DBFactory;
use App\Components\Middleware\Auth\AuthMiddleware;
use App\Components\Middleware\Auth\AuthMiddlewareFactory;
use App\Components\Middleware\Guest\GuestMiddleware;
use App\Components\Middleware\Guest\GuestMiddlewareFactory;
use App\Components\Middleware\MiddlewareManagerFactory;
use App\Components\Middleware\MiddlewareManagerInterface;
use App\Components\Middleware\Pattern\MiddlewarePipeline;
use App\Components\Middleware\Pattern\MiddlewarePipelineInterface;
use App\Components\Middleware\RequestHandlerFactory;
use App\Components\Middleware\StartSession\StartSessionMiddleware;
use App\Components\Middleware\StartSession\StartSessionMiddlewareFactory;
use App\Components\Module\ModuleConfigFactory;
use App\Components\Module\ModuleConfigInterface;
use App\Components\Response\Html\Handlers\Twig;
use App\Components\Response\Html\Handlers\TwigFactory;
use App\Components\Response\Html\HtmlResponseFactory;
use App\Components\Response\Html\HtmlResponseInterface;
use App\Components\Router\ParseRequestBody\RequestBodyParser;
use App\Components\Router\ParseRequestBody\RequestBodyParserFactory;
use App\Components\Router\ParseRequestBody\RequestBodyParserInterface;
use App\Components\Router\RouterFactory;
use App\Components\Router\RouterInterface;
use App\Components\Session\Session;
use App\Components\Session\SessionInterface;
use App\Components\Template\TemplateBuilderFactory;
use App\Components\Template\TemplateBuilderInterface;
use App\Controllers\ApiController;
use App\Controllers\HomeController;
use App\Controllers\HomeControllerFactory;
use App\Factories\ConnectionFactory;
use App\Factories\EntityManagerFactory;
use App\Factories\ServerRequestFactory;
use App\Factories\TwigEnvironmentFactory;
use App\Middlewares\ResponseTypeMiddleware;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

return function (DependenciesRepositoryInterface $dependenciesRepository) {
    //---First party---
    $dependenciesRepository->setInvokable(Session::class);
    $dependenciesRepository->setAlias(SessionInterface::class, Session::class);
    $dependenciesRepository->setFactory(DB::class, DBFactory::class);
    $dependenciesRepository->setFactory(RouterInterface::class, RouterFactory::class);
    $dependenciesRepository->setFactory(RequestBodyParserInterface::class, RequestBodyParserFactory::class);

    $dependenciesRepository->setFactory(TemplateBuilderInterface::class, TemplateBuilderFactory::class);
    $dependenciesRepository->setFactory(Twig::class, TwigFactory::class);
    $dependenciesRepository->setFactory(HtmlResponseInterface::class, HtmlResponseFactory::class);
    $dependenciesRepository->setFactory(ModuleConfigInterface::class, ModuleConfigFactory::class);

    $dependenciesRepository->setFactory(MiddlewareManagerInterface::class, MiddlewareManagerFactory::class);
    $dependenciesRepository->setInvokable(MiddlewarePipeline::class);
    $dependenciesRepository->setAlias(MiddlewarePipelineInterface::class, MiddlewarePipeline::class);
    $dependenciesRepository->setFactory(StartSessionMiddleware::class, StartSessionMiddlewareFactory::class);
    $dependenciesRepository->setFactory(GuestMiddleware::class, GuestMiddlewareFactory::class);
    $dependenciesRepository->setFactory(AuthMiddleware::class, AuthMiddlewareFactory::class);
    $dependenciesRepository->setInvokable(RequestBodyParser::class);

    $dependenciesRepository->setFactory(HomeController::class, HomeControllerFactory::class);
    $dependenciesRepository->setInvokable(ApiController::class);

    //---Third party---
    $dependenciesRepository->setAlias(ResponseInterface::class, Response::class);
    $dependenciesRepository->setInvokable(Response::class);
    $dependenciesRepository->setFactory(ServerRequestInterface::class, ServerRequestFactory::class);
    $dependenciesRepository->setFactory(RequestHandlerInterface::class, RequestHandlerFactory::class);

    $dependenciesRepository->setFactory(Connection::class, ConnectionFactory::class);
    $dependenciesRepository->setFactory(EntityManagerInterface::class, EntityManagerFactory::class);

    $dependenciesRepository->setFactory(\Twig\Environment::class, TwigEnvironmentFactory::class);
};

<?php

declare(strict_types=1);

use App\Components\Config\Config;
use App\Components\Config\ConfigInterface;
use App\Components\Container\DependenciesRepositoryInterface;
use App\Components\Controller\Controller;
use App\Components\DB\DB;
use App\Components\DB\DBFactory;
use App\Components\Middleware\MiddlewareManagerFactory;
use App\Components\Middleware\MiddlewareManagerInterface;
use App\Components\Middleware\RequestHandlerFactory;
use App\Components\Response\Html\Handlers\Twig;
use App\Components\Response\Html\Handlers\TwigFactory;
use App\Components\Response\Html\HtmlResponseFactory;
use App\Components\Response\Html\HtmlResponseInterface;
use App\Components\Session\Session;
use App\Components\Session\SessionInterface;
use App\Controllers\ApiController;
use App\Controllers\HomeController;
use App\Controllers\HomeControllerFactory;
use App\Factories\ConnectionFactory;
use App\Factories\EntityManagerFactory;
use App\Factories\ServerRequestFactory;
use App\Factories\TwigEnvironmentFactory;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\Factories\AuthMiddlewareFactory;
use App\Middlewares\Factories\GuestMiddlewareFactory;
use App\Middlewares\Factories\StartSessionMiddlewareFactory;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\ResponseTypeMiddleware;
use App\Middlewares\StartSessionMiddleware;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmozart\Assert\Assert;

return function (DependenciesRepositoryInterface $dependenciesRepository) {
    //---First party---
    $dependenciesRepository->setAlias(SessionInterface::class, Session::class);
    $dependenciesRepository->setInvokable(Session::class);
    $dependenciesRepository->setFactory(DB::class, DBFactory::class);
    $dependenciesRepository->setFactory(Twig::class, TwigFactory::class);
    $dependenciesRepository->setFactory(HtmlResponseInterface::class, HtmlResponseFactory::class);

    $dependenciesRepository->setFactory(MiddlewareManagerInterface::class, MiddlewareManagerFactory::class);
    $dependenciesRepository->setFactory(StartSessionMiddleware::class, StartSessionMiddlewareFactory::class);
    $dependenciesRepository->setFactory(GuestMiddleware::class, GuestMiddlewareFactory::class);
    $dependenciesRepository->setFactory(AuthMiddleware::class, AuthMiddlewareFactory::class);
    $dependenciesRepository->setInvokable(ResponseTypeMiddleware::class);

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

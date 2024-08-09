<?php

declare(strict_types=1);

namespace App;

use App\Contracts\ConfigInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use App\Contracts\RouterInterface;
use Psr\Container\ContainerInterface;
use App\Exceptions\RouteNotFoundException;

class App
{
    protected RouterInterface $router;
    protected ServerRequestInterface $request;

    public function __construct(
        protected ContainerInterface $container,
        protected ConfigInterface $config
    ) {
    }

    public function run(): void
    {
        echo $this->router->resolve($this->request);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }
}
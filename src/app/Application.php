<?php

declare(strict_types=1);

namespace App;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Router\Router;
use App\Components\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application
{
    protected RouterInterface $router;
    protected ServerRequestInterface $request;
    protected ResponseInterface $response;

    public function __construct(
        protected ServiceManagerInterface $serviceManager,
    ) {
    }

    public function run()
    {
        echo $this->router->resolve($this->request);
    }

    public function getServiceManager(): ServiceManagerInterface
    {
        return $this->serviceManager;
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}

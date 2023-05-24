<?php

declare(strict_types=1);

namespace App;

use ReflectionException;
use App\Contracts\RouterInterface;
use Psr\Container\ContainerInterface;
use App\Exceptions\RouteNotFoundException;

class App
{
    /**
     * @param Container $container
     * @param Router|null $router
     * @param array $request
     */
    public function __construct(
        protected ContainerInterface $container,
        protected ?RouterInterface $router = null,
        protected array $request = [],
    ) {
        //
    }

    /**
     * @return void
     * @throws Exceptions\ContainerException
     * @throws Exceptions\MiddlewareException
     * @throws ReflectionException
     */
    public function run(): void
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouteNotFoundException) {
            http_response_code(404);

            echo View::make('errors/404');
        }
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @return Router|null
     */
    public function getRouter(): ?Router
    {
        return $this->router;
    }

    /**
     * @param Router $router
     * @return void
     */
    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    /**
     * @return array|null
     */
    public function getRequest(): ?array
    {
        return $this->request;
    }

    /**
     * @param array $request
     * @return void
     */
    public function setRequest(array $request): void
    {
        $this->request = $request;
    }
}
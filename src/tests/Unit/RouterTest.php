<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Router;
use App\Container;
use PHPUnit\Framework\TestCase;
use App\Exceptions\RouteNotFoundException;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();
        // initialize router
        $container = new Container();
        $this->router = new Router($container);
    }

    /**
     * @test
     */
    public function there_are_no_routes_when_router_is_created(): void
    {
        $this->assertEmpty($this->router->routes());
    }

    /**
     * @test
     */
    public function route_can_be_registered(): void
    {
        $this->router->register('get', '/users', ['UserController', 'index']);
        $expectation = [
            [
                'method' => 'get',
                'route' => '/users',
                'action' => ['UserController', 'index'],
                'middleware' => null,
            ]
        ];
        // assert expectation
        $this->assertEquals($expectation, $this->router->routes());
    }

    /**
     * @test
     */
    public function get_route_can_be_registered(): void
    {
        $this->router->get('/users', ['UserController', 'index']);
        $expectation = [
            [
                'method' => 'get',
                'route' => '/users',
                'action' => ['UserController', 'index'],
                'middleware' => null,
            ]
        ];
        // assert expectation
        $this->assertEquals($expectation, $this->router->routes());
    }

    /**
     * @test
     */
    public function post_route_can_be_registered(): void
    {
        $this->router->post('/user', ['UserController', 'store']);
        $expectation = [
            [
                'method' => 'post',
                'route' => '/user',
                'action' => ['UserController', 'store'],
                'middleware' => null,
            ]
        ];
        // assert expectation
        $this->assertEquals($expectation, $this->router->routes());
    }

    /**
     * @test
     * @dataProvider \Tests\DataProvider\RouterDataProvider::routeCases
     */
    public function ThatItThrowsRouteNotFoundException(
        string $requestUri,
        string $requestMethod
    ) {
        // anonymous class
        $users = new class() {
            public function delete(): bool
            {
                return true;
            }
        };
        // registered routes
        $this->router->post('/users', [$users::class, 'store']);
        $this->router->get('/users', ['Users', 'index']);
        // assert expectation
        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    /**
     * @test
     */
    public function it_resolve_route_from_a_closure()
    {
        $userIds = [1, 2, 3];
        $this->router->get('/users', fn() => $userIds);
        // assert expectation
        $this->assertEquals($userIds, $this->router->resolve('/users', 'get'));
    }

    /**
     * @test
     */
    public function it_resolve_route()
    {
        // anonymous class
        $users = new class() {
            public function index(): bool
            {
                return false;
            }
        };
        $this->router->get('/users', [$users::class, 'index']);
        // assert expectation
        $this->assertSame(false, $this->router->resolve('/users', 'get'));
    }
}
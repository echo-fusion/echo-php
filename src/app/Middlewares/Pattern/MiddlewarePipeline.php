<?php

declare(strict_types=1);

namespace App\Middlewares\Pattern;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplQueue;

class MiddlewarePipeline
{
    /** @var SplQueue<MiddlewareInterface> */
    private SplQueue $pipeline;

    public function __construct()
    {
        $this->pipeline = new SplQueue();
    }

    public function isPipeLineEmpty(): bool
    {
        return $this->pipeline->isEmpty();
    }

    public function __clone()
    {
        $this->pipeline = clone $this->pipeline;
    }

    public function pipe(MiddlewareInterface $middleware): void
    {
        $this->pipeline->enqueue($middleware);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $next = new NextMiddleware($this->pipeline, $handler);

        return $next->handle($request);
    }
}
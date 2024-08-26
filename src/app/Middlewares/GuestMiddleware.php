<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Components\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GuestMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SessionInterface $session)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->session->has('user') && $this->session->get('user');

        // @todo: redirect to home
        if ($user) {
            header('location: /');
            exit();
        }

        return $handler->handle($request);
    }
}

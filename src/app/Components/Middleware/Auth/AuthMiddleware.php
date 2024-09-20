<?php

declare(strict_types=1);

namespace App\Components\Middleware\Auth;

use App\Components\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SessionInterface $session)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $this->session->has('user') && $this->session->get('user');

        // @todo: redirect to login or sent 401 response
        if (!$user) {
            header('location: /');
            exit();
        }

        return $handler->handle($request);
    }
}

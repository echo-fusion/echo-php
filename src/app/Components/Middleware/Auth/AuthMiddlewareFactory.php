<?php

declare(strict_types=1);

namespace App\Components\Middleware\Auth;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Session\SessionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Webmozart\Assert\Assert;

class AuthMiddlewareFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): MiddlewareInterface
    {
        $session = $serviceManager->get(SessionInterface::class);
        Assert::isInstanceOf($session, SessionInterface::class);

        return new AuthMiddleware($session);
    }
}

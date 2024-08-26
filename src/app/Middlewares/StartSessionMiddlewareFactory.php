<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Session\SessionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Webmozart\Assert\Assert;

class StartSessionMiddlewareFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): MiddlewareInterface
    {
        $session = $serviceManager->get(SessionInterface::class);
        Assert::isInstanceOf($session, SessionInterface::class);

        return new StartSessionMiddleware($session);
    }
}

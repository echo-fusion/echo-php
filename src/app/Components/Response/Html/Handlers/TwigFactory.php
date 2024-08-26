<?php

declare(strict_types=1);

namespace App\Components\Response\Html\Handlers;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Response\Html\HtmlResponseInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

class TwigFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): HtmlResponseInterface
    {
        $twigEnvironment = $serviceManager->get(Environment::class);
        Assert::isInstanceOf($twigEnvironment, Environment::class);

        return new Twig($twigEnvironment);
    }
}

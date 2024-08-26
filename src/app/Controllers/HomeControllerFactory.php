<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Controller\Controller;
use App\Components\Response\Html\HtmlResponseInterface;
use Webmozart\Assert\Assert;

class HomeControllerFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): HomeController
    {
        $htmlResponse = $serviceManager->get(HtmlResponseInterface::class);
        Assert::isInstanceOf($htmlResponse, HtmlResponseInterface::class);

        return new HomeController($htmlResponse);
    }
}

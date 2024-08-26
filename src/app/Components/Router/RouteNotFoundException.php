<?php

declare(strict_types=1);

namespace App\Components\Router;

class RouteNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = '404 Not Found!';
}

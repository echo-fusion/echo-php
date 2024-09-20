<?php

declare(strict_types=1);

namespace App\Components\Router;

class DuplicateRouteException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Another route with same name and method is exist!';
}

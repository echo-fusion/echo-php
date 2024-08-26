<?php

declare(strict_types=1);

namespace App\Components\Container;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \Exception implements ContainerExceptionInterface
{
}

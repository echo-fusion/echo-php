<?php

namespace App\Contracts;

use Psr\Container\ContainerInterface;

interface ConfigInterface
{
    public function getMerged(): array;
}

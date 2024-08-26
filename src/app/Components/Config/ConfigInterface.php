<?php

declare(strict_types=1);

namespace App\Components\Config;

interface ConfigInterface
{
    public function getMerged(): array;

    public function has(string $key): bool;
}

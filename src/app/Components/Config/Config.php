<?php

declare(strict_types=1);

namespace App\Components\Config;

class Config implements ConfigInterface
{
    protected array $config;

    public function __construct(array ...$configs)
    {
        $mergedConfig = [];
        foreach ($configs as $config) {
            $mergedConfig = array_merge($mergedConfig, $config);
        }
        $this->config = $mergedConfig;
    }

    public function getMerged(): array
    {
        return $this->config;
    }

    public function has(string $key): bool
    {
        return isset($this->config[$key]);
    }
}

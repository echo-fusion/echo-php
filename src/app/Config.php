<?php

declare(strict_types=1);

namespace App;

use App\Enums\AppEnvironment;

/**
 * @property-read ?array $db
 * @property-read ?string $environment
 */
class Config
{
    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @param array $env
     */
    public function __construct(array $env)
    {
        $appEnv = $env['APP_ENV'] ?? AppEnvironment::Production->value;

        $this->config = [
            'environment' => $appEnv,
            'db' => [
                'host' => $env['DB_HOST'],
                'username' => $env['DB_USER'],
                'password' => $env['DB_PASS'],
                'database' => $env['DB_DATABASE'],
                'driver' => $env['DB_DRIVER'] ?? 'mysql',
            ],
        ];
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}
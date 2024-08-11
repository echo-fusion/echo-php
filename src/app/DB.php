<?php

declare(strict_types=1);

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * @mixin Connection
 */
class DB
{
    public Connection $connection;

    public function __construct(array $connectionParams)
    {
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    /**
     * proxy this class to doctrine if function not exist here
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->connection, $name], $arguments);
    }
}

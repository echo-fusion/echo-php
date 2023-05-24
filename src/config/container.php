<?php

declare(strict_types=1);

use App\DB;
use App\Config;
use App\Container;
use App\Migrations\Migration;


$container = new Container();
// bind dependencies here

$container->bind(Config::class, fn() => new Config($_ENV));

$container->bind(DB::class, function (Container $container) {
    $config = $container->get(Config::class);
    return new DB($config->db);
});

$container->bind(Migration::class, function (Container $container) {
    $db = $container->get(DB::class);
    return new Migration($db);
});

return $container;
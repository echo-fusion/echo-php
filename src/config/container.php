<?php

declare(strict_types=1);

use App\DB;
use App\Config;
use App\Container;
use app\Migrations\Migration;


$container = new Container();
// bind dependencies here

$container->bind(Config::class, fn() => new Config($_ENV));



return $container;
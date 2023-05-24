<?php

declare(strict_types=1);

use App\App;
use Dotenv\Dotenv;

require __DIR__ . '/config/path_constants.php';
require ROOT_PATH . '/vendor/autoload.php';

// load environment file
$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// initial container
$container = require CONFIG_PATH . '/container.php';

return new App($container);

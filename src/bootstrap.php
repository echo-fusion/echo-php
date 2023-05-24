<?php

declare(strict_types=1);

use App\App;

require __DIR__ . '/config/path_constants.php';
require ROOT_PATH . '/vendor/autoload.php';

// initial container
$container = require CONFIG_PATH . '/container.php';

return new App($container);

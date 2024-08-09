<?php

use App\Config;
use App\Enums\AppEnvironment;

$appEnv = isset($_ENV['APP_ENV']) ?
    AppEnvironment::from($_ENV['APP_ENV'])->value :
    AppEnvironment::Production->value;

return (new Config(
    require CONFIG_PATH . '/global.config.php',
    require CONFIG_PATH . sprintf('/environments/%s.config.php', $appEnv),
    require CONFIG_PATH . '/modules.config.php',
));
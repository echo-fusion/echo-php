<?php

declare(strict_types=1);

use PhpCsFixer\Config;

$config = new Config();

$config->setUsingCache(false);

$config
    ->getFinder()
    ->ignoreDotFiles(false)
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/public')
    ->in(__DIR__ . '/tests');

return $config;
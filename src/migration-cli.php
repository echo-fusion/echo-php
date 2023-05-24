<?php

declare(strict_types=1);

use App\App;
use App\Migrations\Migration;

/** @var App $app **/
$app = require __DIR__ . '/bootstrap.php';

/** @var Migration $migration **/
$migration = $app->getContainer()->get(Migration::class);
$migration->run();
<?php

declare(strict_types=1);

use App\Auth;
use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserRepositoryInterface;
use App\DB;
use App\Config;
use App\Container;
use App\Enums\AppEnvironment;
use App\Migrations\Migration;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Session;
use Psr\Container\ContainerInterface;
use Twig\Loader\FilesystemLoader;


$container = new Container();
// bind dependencies here

$container->bind(Config::class, fn() => new Config($_ENV));

$container->bind(DB::class, function (ContainerInterface $container) {
    $config = $container->get(Config::class);
    return new DB($config->db);
});

$container->bind(Migration::class, function (ContainerInterface $container) {
    $db = $container->get(DB::class);
    return new Migration($db);
});

$container->bind(SessionInterface::class, Session::class);
$container->bind(UserInterface::class, fn() => new User());
$container->bind(UserRepositoryInterface::class, UserRepository::class);
$container->bind(AuthInterface::class, Auth::class);

// bind Twig template engine
$container->bind(\Twig\Environment::class, function (ContainerInterface $container) {
    $config = $container->get(Config::class);
    $loader = new FilesystemLoader(VIEW_PATH);
    return new \Twig\Environment($loader, [
        'cache' => STORAGE_PATH . '/cache/templates',
        'auto_reload' => $config->environment == AppEnvironment::Development->value,
    ]);
});

return $container;
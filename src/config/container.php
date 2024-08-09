<?php

declare(strict_types=1);

use App\Auth;
use App\Config;
use App\Contracts\AuthInterface;
use App\Contracts\MiddlewareFactoryInterface;
use App\Contracts\SessionInterface;
use App\DB;
use App\Enums\AppEnvironment;
use App\MiddlewareFactory;
use App\Modules\User\Contracts\UserRepositoryInterface;
use App\Modules\User\Repositories\UserRepository;
use App\Session;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Guzzle\Stream\Stream;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Loader\FilesystemLoader;

return function (ContainerInterface $container) {
    // bind core dependencies
    $container->bind(SessionInterface::class, Session::class);
    $container->bind(UserRepositoryInterface::class, UserRepository::class);
    $container->bind(AuthInterface::class, Auth::class);
    $container->bind(Config::class, function (ContainerInterface $container) {
        return require CONFIG_PATH . '/application.config.php';
    });
    $container->bind(DB::class, function (ContainerInterface $container) {
        $config = $container->get(Config::class);
        return new DB($config->getMerged()['database_info']);
    });
    $container->bind(MiddlewareFactoryInterface::class, function (ContainerInterface $container) {
        return new MiddlewareFactory($container);
    });

    // PSR-7 implementations
    $container->bind(ServerRequestInterface::class, function (ContainerInterface $container) {
        return new ServerRequest(
            method: $_SERVER['REQUEST_METHOD'] ?? 'GET',
            uri: $_SERVER['REQUEST_URI'],
            headers: getallheaders(),
            body: new Stream(fopen('php://temp', 'r')),
            version: isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1',
            serverParams: $_SERVER
        );
    });
    $container->bind(ResponseInterface::class, function (ContainerInterface $container) {
        return new Response();
    });
    $container->bind(RequestHandlerInterface::class, function (ContainerInterface $container) {
        return new class() implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return new Response();
            }
        };
    });

    // Doctrine
    $container->bind(EntityManagerInterface::class, function (ContainerInterface $container) {
        $db = $container->get(DB::class);
        return new EntityManager(
            $db->connection,
            ORMSetup::createAttributeMetadataConfiguration([APP_PATH . '/Entities'])
        );
    });
    $container->bind(Connection::class, function (ContainerInterface $container) {
        /** @var Config $config */
        $config = $container->get(Config::class);
        return DriverManager::getConnection($config->getMerged()['database_info']);
    });

    // Twig
    $container->bind(\Twig\Environment::class, function (ContainerInterface $container) {
        /** @var Config $config */
        $config = $container->get(Config::class);
        $environment = $config->getMerged()['environment'];
        $loader = new FilesystemLoader(VIEW_PATH);
        return new \Twig\Environment($loader, [
            'cache' => STORAGE_PATH . '/cache/templates',
            'auto_reload' => $environment === AppEnvironment::Development->value,
        ]);
    });
};
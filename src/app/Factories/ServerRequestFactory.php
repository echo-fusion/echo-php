<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\Container\ServiceManagerInterface;
use Guzzle\Stream\Stream;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): ServerRequestInterface
    {
        return new ServerRequest(
            method: $_SERVER['REQUEST_METHOD'] ?? 'GET',
            uri: $_SERVER['REQUEST_URI'],
            headers: getallheaders(),
            body: new Stream(fopen('php://temp', 'r')),
            version: isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1',
            serverParams: $_SERVER
        );
    }
}

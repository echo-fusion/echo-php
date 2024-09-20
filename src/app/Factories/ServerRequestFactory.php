<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\Container\ServiceManagerInterface;
use App\Components\Router\HttpMethod;
use Guzzle\Stream\Stream;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\UploadedFile;

class ServerRequestFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): ServerRequestInterface
    {
        $request = new ServerRequest(
            method: $_SERVER['REQUEST_METHOD'] ?? HttpMethod::GET,
            uri: $_SERVER['REQUEST_URI'],
            headers: getallheaders(),
            body: new Stream(fopen('php://input', 'r+')),
            version: isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1',
            serverParams: $_SERVER,
        );

        $uploadedFiles = $this->normalizeUploadedFiles($_FILES);

        return $request->withUploadedFiles($uploadedFiles);
    }

    private function normalizeUploadedFiles(array $files): array
    {
        $normalized = [];

        foreach ($files as $key => $value) {
            if (is_array($value['name'])) {
                // Handle multi-file input (arrays of files)
                $normalized[$key] = [];
                foreach ($value['name'] as $index => $name) {
                    $normalized[$key][$index] = new UploadedFile(
                        $value['tmp_name'][$index],
                        $value['size'][$index],
                        $value['error'][$index],
                        $value['name'][$index],
                        $value['type'][$index]
                    );
                }
            } else {
                // Single file input
                $normalized[$key] = new UploadedFile(
                    $value['tmp_name'],
                    $value['size'],
                    $value['error'],
                    $value['name'],
                    $value['type']
                );
            }
        }

        return $normalized;
    }
}

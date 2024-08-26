<?php

declare(strict_types=1);

namespace App\Components\Response;

use Guzzle\Stream\Stream;
use Guzzle\Stream\StreamInterface;
use GuzzleHttp\Psr7\Response;

class HtmlResponse extends Response
{
    use InjectContentTypeTrait;

    public function __construct($html, int $status = 200, array $headers = [])
    {
        parent::__construct(
            status: $status,
            headers: $this->injectContentType('text/html; charset=utf-8', $headers),
            body: $this->createBody($html)
        );
    }

    private function createBody(string $html): StreamInterface
    {
        $body = new Stream(fopen('php://temp', 'wb+'));
        $body->write($html);
        $body->rewind();

        return $body;
    }

    public function __toString(): string
    {
        return $this->getBody()->getContents();
    }
}

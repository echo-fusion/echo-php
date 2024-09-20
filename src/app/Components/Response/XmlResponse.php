<?php

declare(strict_types=1);

namespace App\Components\Response;

use GuzzleHttp\Psr7\Response;

class XmlResponse extends Response
{
    use InjectHeaderTrait;

    public function __construct(string $xml, int $status = 302, array $headers = [])
    {
        parent::__construct(
            status: $status,
            headers: $this->injectHeader('content-type', 'application/xml; charset=UTF-8', $headers),
            body: $xml
        );
    }

    public function __toString(): string
    {
        return $this->getBody()->getContents();
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Response;

use GuzzleHttp\Psr7\Response;

class RedirectResponse extends Response
{
    use InjectHeaderTrait;

    public function __construct(string $url, int $status = 302, array $headers = [])
    {
        parent::__construct(
            status: $status,
            headers: $this->injectHeader('Location', $url, $headers),
            body: $this->createBody($url)
        );
    }

    private function createBody(string $url)
    {
        return sprintf('<!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="refresh" content="0;url=\'%1$s\'" />
            
                    <title>Redirecting to %1$s</title>
                </head>
                <body>
                    Redirecting to <a href="%1$s">%1$s</a>.
                </body>
            </html>', htmlspecialchars($url, \ENT_QUOTES, 'UTF-8'));
    }

    public function __toString(): string
    {
        return $this->getBody()->getContents();
    }
}

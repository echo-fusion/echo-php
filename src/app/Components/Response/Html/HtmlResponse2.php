<?php

declare(strict_types=1);

namespace App\Components\Response\Html;

use App\Components\Response\Html\HtmlResponseInterface;
use GuzzleHttp\Psr7\Response;

class HtmlResponse2 extends Response
{
    public function __construct(protected HtmlResponseInterface $htmlResponse)
    {

    }

    public function render(string $template, array $params = []): string
    {
        return $this->htmlResponse->render($template, $params);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Response\Html\Handlers;

use App\Components\Response\Html\HtmlResponseInterface;
use Twig\Environment;

class Twig implements HtmlResponseInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public function render(string $name, array $params = []): string
    {
        return $this->twig->render($name, $params);
    }
}

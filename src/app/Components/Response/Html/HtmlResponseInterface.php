<?php

declare(strict_types=1);

namespace App\Components\Response\Html;

interface HtmlResponseInterface
{
    /**
     * @param non-empty-string $name
     * @param array $params
     * @return mixed
     */
    public function render(string $name, array $params = []): string;
}

<?php

declare(strict_types=1);

namespace App\Components\Template;

interface TemplateBuilderInterface
{
    public function getPath(): string;

    public function setPath(string $path): string;
}
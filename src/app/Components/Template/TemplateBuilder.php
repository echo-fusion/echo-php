<?php

namespace App\Components\Template;

class TemplateBuilder implements TemplateBuilderInterface
{
    private string $path;

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): string
    {
        $this->path = $path;
    }
}
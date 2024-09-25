<?php

declare(strict_types=1);

namespace App\Components\Module;

use App\Components\Container\DependenciesRepositoryInterface;
use App\Components\Router\RouterInterface;
use App\Components\Template\TemplateBuilderInterface;

interface ModuleConfigInterface
{
    public function getRouter(): RouterInterface;
    public function getDependenciesRepository(): DependenciesRepositoryInterface;
    public function getTemplateBuilder(): ?TemplateBuilderInterface;
}

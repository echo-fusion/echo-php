<?php

namespace App\Components\Template;

use App\Components\Container\ServiceManagerInterface;

class TemplateBuilderFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): TemplateBuilderInterface
    {
        return new TemplateBuilder();
    }
}
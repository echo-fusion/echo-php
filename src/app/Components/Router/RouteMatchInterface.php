<?php

namespace App\Components\Router;

interface RouteMatchInterface
{
    const REQUEST_KEY = 'routeMatch';

    public function setMatchedRouteName(string $name): self;

    public function getMatchedRouteName(): string;

    public function setParams(array $params): self;

    public function setParam(string $name, $value): self;

    public function getParams(): array;

    public function getParam(string $name): ?string;
}
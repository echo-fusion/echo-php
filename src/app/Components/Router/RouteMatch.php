<?php

declare(strict_types=1);

namespace App\Components\Router;

use function array_key_exists;

class RouteMatch implements RouteMatchInterface
{
    protected string $matchedRouteName;

    protected array $params = [];

    protected array $body = [];

    public function setMatchedRouteName(string $name): self
    {
        $this->matchedRouteName = $name;
        return $this;
    }

    public function getMatchedRouteName():string
    {
        return $this->matchedRouteName;
    }

    public function setParam(string $name, $value): self
    {
        $this->params[$name] = $value;
        return $this;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $name): ?string
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @return RouteMatch
     */
    public function setBody(array $body): RouteMatch
    {
        $this->body = $body;
        return $this;
    }
}

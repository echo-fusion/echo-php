<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        protected string $view,
        protected array $params = []
    ) {
    }

    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string
    {
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        if (!$viewPath) {
            throw new ViewNotFoundException();
        }

        // access parameter as a variable in view file
        extract($this->params);

        ob_start();

        include $viewPath;

        return (string)ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }
}

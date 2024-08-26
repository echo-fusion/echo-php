<?php

declare(strict_types=1);

namespace App\Components\Response\Html\Handlers;

use App\Components\Response\Html\HtmlResponseInterface;

class View implements HtmlResponseInterface
{
    //    public function __construct(private string $viewPath)
    //    {
    //    }

    public function render(string $name, array $params = []): string
    {
        //@todo: it should be dynamically define according to config (app & modules)
        $viewPath = VIEW_PATH . '/' . $name . '.php';

        if (!$viewPath) {
            throw new ViewNotFoundException();
        }

        extract($params);
        ob_start();

        include $viewPath;

        return (string)ob_get_clean();
    }
}

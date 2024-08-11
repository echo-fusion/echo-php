<?php

declare(strict_types=1);

namespace App\Controllers;

use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function home(): string
    {
        return $this->twig->render('home.twig');
    }
}

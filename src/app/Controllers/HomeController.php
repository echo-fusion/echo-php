<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Controller\Controller;
use App\Components\Response\Html\HtmlResponseInterface;
use App\Components\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends Controller
{
    public function __construct(
        protected readonly HtmlResponseInterface $html,
    ) {
    }

    public function home(ServerRequestInterface $request)
    {
        return new HtmlResponse(
            $this->html->render('home.twig')
        );
    }
}

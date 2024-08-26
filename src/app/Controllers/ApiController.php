<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Controller\Controller;
use App\Components\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class ApiController extends Controller
{
    public function index(): ResponseInterface
    {
        return new JsonResponse(['hello' => 'world']);
    }
}

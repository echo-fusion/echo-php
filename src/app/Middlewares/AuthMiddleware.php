<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Contracts\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $user = $_SESSION['user'] ?? false;
        if (!$user) {
            header('location: /');
            exit();
        }
    }
}
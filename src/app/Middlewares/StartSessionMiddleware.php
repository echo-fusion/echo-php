<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Contracts\MiddlewareInterface;
use App\Contracts\SessionInterface;

class StartSessionMiddleware implements MiddlewareInterface
{
    /**
     * @param SessionInterface $session
     */
    public function __construct(private readonly SessionInterface $session)
    {
        //
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->session->start();
    }
}
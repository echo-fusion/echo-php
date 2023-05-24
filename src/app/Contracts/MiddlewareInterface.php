<?php

declare(strict_types=1);

namespace App\Contracts;

interface MiddlewareInterface
{
    public function handle(): void;
}
<?php

declare(strict_types=1);

namespace App\Components\Auth;

use App\Entities\User;

interface AuthInterface
{
    public function user(): ?User;

    public function attemptLogin(array $credentials): bool;

    public function logOut(): void;

    public function register(array $data): User;

    public function logIn(User $user): void;
}

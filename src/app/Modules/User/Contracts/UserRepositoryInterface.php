<?php

declare(strict_types=1);

namespace App\Modules\User\Contracts;

use App\Entities\User;

interface UserRepositoryInterface
{
    public function create(string $name, string $email, string $password): User;

    public function all();

    public function find(int $id): ?User;

    public function getByCredentials(string $email, string $password): ?User;
}
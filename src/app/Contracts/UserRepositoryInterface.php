<?php

declare(strict_types=1);

namespace App\Contracts;

interface UserRepositoryInterface
{
    public function create(array $data): UserInterface;

    public function all();

    public function find(int $id): ?UserInterface;

    public function getByCredentials(array $credentials): ?UserInterface;
}
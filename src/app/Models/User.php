<?php

declare(strict_types=1);

namespace App\Models;

use DateTime;

class User extends AbstractModel
{
    private string $name;
    private string $email;
    private string $password;
    private DateTime $create_at;

    public function __construct()
    {
        $this->table = 'users';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return DateTime
     */
    public function getCreateAt(): DateTime
    {
        return $this->create_at;
    }

    /**
     * @param DateTime $create_at
     */
    public function setCreateAt(DateTime $create_at): void
    {
        $this->create_at = $create_at;
    }
}
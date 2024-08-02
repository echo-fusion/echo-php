<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\UserInterface;
use DateTime;

class User extends AbstractModel implements UserInterface
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private DateTime $create_at;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return int
     */
    public function setId(int $id)
    {
        return $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
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
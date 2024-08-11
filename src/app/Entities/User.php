<?php

declare(strict_types=1);

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[Entity]
#[Table(name: 'users')]
#[HasLifecycleCallbacks]
class User
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column(type: 'string', length: 50)]
    private string $name;

    #[Column(type: 'string', length: 100)]
    private string $email;

    #[Column(type: 'text')]
    private string $password;

    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[PrePersist]
    public function onPrePersist(LifecycleEventArgs $args)
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreateAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}

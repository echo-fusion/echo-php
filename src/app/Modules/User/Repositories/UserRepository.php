<?php

declare(strict_types=1);

namespace App\Modules\User\Repositories;

use App\Entities\User;
use App\Modules\User\Contracts\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function create(string $name, string $email, string $password): User
    {
        $user = new User();
        $user->setName($name)
            ->setEmail($email)
            ->setPassword(password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function all()
    {
        $query = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->orderBy('i.createdAt', 'desc')
            ->getQuery();

        return $query->getResult();
    }

    public function find($id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }

    public function getByCredentials(string $email, string $password): ?User
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder->select('u')
            ->from(User::class, 'u')
            ->where(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->eq('u.email', ':email'),
                    $queryBuilder->expr()->eq('u.password', ':password'),
                )
            )
            ->setParameter('email', $email)
            ->setParameter('password', $password)
            ->getQuery();

        return $query->getResult();
    }
}

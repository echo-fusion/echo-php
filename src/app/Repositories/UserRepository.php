<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Contracts\UserInterface;
use App\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $model
     */
    public function __construct(protected User $model)
    {
        //
    }

    /**
     * @param array $data
     * @return UserInterface
     */
    public function create(array $data): UserInterface
    {
        $stmt = $this->model->db->prepare(
            'INSERT INTO users (name, email, password, created_at) VALUES (?,?,?,?)'
        );

        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            new \DateTime(),
        ]);

        $result = $this->find((int)$this->db->lastInsertId());
        $userModel = new User();
        $userModel->setId($result['id']);
        $userModel->setName($result['name']);
        $userModel->setId($result['email']);
        $userModel->setPassword($result['password']);

        return $userModel;
    }

    /**
     * @return mixed
     */
    public function all(): mixed
    {
        $stmt = $this->db->prepare('SELECT * FROM users order by created_at DESC');

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param $id
     * @return UserInterface|null
     */
    public function find($id): ?UserInterface
    {
        $stmt = $this->db->prepare('SELECT * FROM users where id=?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        if (!is_null($result)) {
            $userModel = new User();
            $userModel->setEmail($result['email']);
            $userModel->setPassword($result['password']);
            return $userModel;
        }

        return null;
    }

    /**
     * @param array $credentials
     * @return UserInterface|null
     */
    public function getByCredentials(array $credentials): ?UserInterface
    {
        $stmt = $this->db->prepare('SELECT * FROM users where email=?');
        $stmt->execute([$credentials['email']]);
        $result = $stmt->fetch();

        if (!is_null($result)) {
            $userModel = new User();
            $userModel->setEmail($result['email']);
            $userModel->setPassword($result['password']);
            return $userModel;
        }

        return null;
    }
}
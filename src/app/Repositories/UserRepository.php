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
     * @return int
     */
    public function create(array $data): int
    {
        $stmt = $this->model->db->prepare(
            'INSERT INTO blogs (title, description,image_url, created_at) VALUES (?,?,?,?)'
        );

        $stmt->execute([
            $data['name'],
            password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            new \DateTime(),
        ]);

        return (int)$this->db->lastInsertId();
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
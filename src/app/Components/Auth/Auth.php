<?php

declare(strict_types=1);

namespace App\Components\Auth;

use App\Components\Session\SessionInterface;
use App\Components\User\UserRepositoryInterface;
use App\Entities\User;

class Auth implements AuthInterface
{
    private ?User $user = null;

    /**
     * @param SessionInterface $session
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private readonly SessionInterface $session,
        private readonly UserRepositoryInterface $userRepository
    ) {
        //
    }

    /**
     * @return User|null
     */
    public function user(): ?User
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get('user');
        if (!$userId) {
            return null;
        }

        $user = $this->userRepository->find($userId);
        if (!$user) {
            return null;
        }

        $this->user = $user;

        return $this->user;
    }

    /**
     * @param array $credentials
     * @return bool
     * @throws ValidationException
     */
    public function attemptLogin(array $credentials): bool
    {
        $email = $credentials['email'];
        $password = $credentials['password'];

        $user = $this->userRepository->getByCredentials($email, $password);
        if (!$user) {
            return false;
        }

        if (!password_verify($credentials['password'], $user->getPassword())) {
            throw new ValidationException(['Email or password is not correct!']);
        }

        // make user logged in
        $this->logIn($user);

        return true;
    }

    /**
     * @param User $user
     * @return void
     */
    public function logIn(User $user): void
    {
        $this->session->regenerate();
        $this->session->put('user', $user->getId());

        $this->user = $user;
    }

    public function logOut(): void
    {
        $this->session->forget('user');
        $this->session->regenerate();

        $this->user = null;
    }

    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        $user = $this->userRepository->create(
            $data['name'],
            $data['email'],
            $data['password'],
        );

        // make user logged in
        $this->logIn($user);

        return $user;
    }
}

<?php

declare(strict_types=1);

namespace App;

use App\Contracts\UserRepositoryInterface;
use App\Exceptions\ValidationException;
use App\Contracts\UserInterface;
use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;

class Auth implements AuthInterface
{
    /**
     * @var UserInterface|null
     */
    private ?UserInterface $user = null;

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
     * @return UserInterface|null
     */
    public function user(): ?UserInterface
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
        $user = $this->userRepository->getByCredentials($credentials);
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
     * @param UserInterface $user
     * @return void
     */
    public function logIn(UserInterface $user): void
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
     * @return UserInterface
     */
    public function register(array $data): UserInterface
    {
        $user = $this->userRepository->create($data);

        // make user logged in
        $this->logIn($user);

        return $user;
    }
}
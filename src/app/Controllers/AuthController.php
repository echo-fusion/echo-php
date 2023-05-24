<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
use App\Exceptions\ValidationException;
use App\View;
use Valitron\Validator;

class AuthController extends AbstractController
{
    /**
     * @param AuthInterface $auth
     */
    public function __construct(
        private readonly AuthInterface $auth,
    ) {
        //
    }

    /**
     * @return View
     */
    public function loginPage(): View
    {
        return View::make('auth/login');
    }

    /**
     * @return void
     */
    public function login()
    {
        $input = $_POST;
        try {
            $validator = new Validator($input);
            $validator->rule('required', ['email', 'password', 'password_confirmation']);
            $validator->rule('email', ['email']);
            $validator->rule('equals', 'password_confirmation', 'password');
            if (!$this->auth->attemptLogin($input)) {
                throw new ValidationException([
                    'password' => ['You have entered an invalid username or password']
                ]);
            }
            // redirect
            http_response_code(302);

            header('Location: /blogs');
            exit;
        } catch (\Throwable $exception) {

        }
    }

    /**
     * @return View
     */
    public function registerPage(): View
    {
        return View::make('auth/register');
    }

    /**
     * @return void
     */
    public function register()
    {
        $input = $_POST;
        try {
            $validator = new Validator($input);
            $validator->rule('required', ['email', 'password', 'password_confirmation']);
            $validator->rule('email', ['email']);
            $validator->rule('equals', 'password_confirmation', 'password');

            $user = $this->auth->register($input);

            // redirect
            http_response_code(302);

            header('Location: /blogs');
            exit;
        } catch (\Throwable $exception) {

        }
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->auth->logOut();

        // redirect
        header('Location: /');
        exit();
    }
}
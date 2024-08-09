<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\AuthInterface;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Valitron\Validator;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly AuthInterface $auth,
        private readonly Environment $twig
    ) {
    }

    public function loginPage(): string
    {
        return $this->twig->render('auth/login.twig');
    }

    public function login(ServerRequestInterface $request)
    {
        var_dump();die();


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

            header('Location: /');
            exit;
        } catch (\Throwable $exception) {

        }
    }

    public function registerPage(): string
    {
        return $this->twig->render('auth/register.twig');
    }

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

            header('Location: /');
            exit;
        } catch (\Throwable $exception) {

        }
    }


    public function logout()
    {
        $this->auth->logOut();

        // redirect
        header('Location: /');
        exit();
    }
}
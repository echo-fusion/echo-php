<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Auth\AuthInterface;
use App\Components\Auth\ValidationException;
use App\Components\Controller\Controller;
use App\Components\Response\Html\HtmlResponseInterface;
use App\Components\Response\HtmlResponse;
use App\Components\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Valitron\Validator;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthInterface $auth,
        protected readonly HtmlResponseInterface $html,
    ) {
    }

    public function loginPage(): ResponseInterface
    {
        return new HtmlResponse(
            $this->html->render('auth/login.twig')
        );
    }

    public function registerPage(): ResponseInterface
    {
        return new HtmlResponse(
            $this->html->render('auth/register.twig')
        );
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getAttributes();
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

            return new RedirectResponse('/', 301);
        } catch (\Throwable $exception) {
            return new RedirectResponse('/login', 301);
        }
    }

    public function register(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getAttributes();
        try {
            $validator = new Validator($input);
            $validator->rule('required', ['email', 'password', 'password_confirmation']);
            $validator->rule('email', ['email']);
            $validator->rule('equals', 'password_confirmation', 'password');

            $user = $this->auth->register($input);

            return new RedirectResponse('/', 301);
        } catch (\Throwable $exception) {
            return new RedirectResponse('/register', 301);
        }
    }

    public function logout(): ResponseInterface
    {
        $this->auth->logOut();

        return new RedirectResponse('/', 301);
    }
}

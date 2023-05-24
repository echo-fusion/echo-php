<?php

declare(strict_types=1);

namespace App\Controllers;

use Twig\Environment;
use Valitron\Validator;

class BlogController extends AbstractController
{
    /**
     * @param Environment $twig
     */
    public function __construct(protected Environment $twig)
    {
        //
    }

    /**
     * @return string
     */
    public function index(): string
    {
        return $this->twig->render('blogs\index.twig', [
            'blogs' => [],
            'pages' => 1,
        ]);
    }

    /**
     * @return string
     */
    public function show(): string
    {
        if (!isset($_GET['id'])) {
            // redirect to show method
            header('Location: /blogs');
            exit;
        }
        $id = (int)$_GET['id'];
        return $this->twig->render('blogs/detail.twig', ['blog' => $this->blogRepo->find($id)]);
    }

    /**
     * @return string
     */
    public function create(): string
    {
        return $this->twig->render('blogs/form.twig');
    }

    /**
     * @return void
     */
    public function store()
    {
        $input = $_POST;
        try {
            $validator = new Validator($input);
            $validator->rule('required', ['title', 'description', 'image_url']);
            $validator->rule('url', 'image_url');
            // create blog

            // redirect to show method
            header('Location: /blogs');
            exit;
        } catch (\Throwable $exception) {
        }
    }
}
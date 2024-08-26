<?php

declare(strict_types=1);

namespace App\Components\Response\Html\Handlers;

class ViewNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'View Not Found!';
}

<?php

declare(strict_types=1);

namespace App\Exceptions;

class ViewNotFoundException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'View Not Found!';
}

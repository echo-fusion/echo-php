<?php

declare(strict_types=1);

namespace App\Components\Response\Json;

use Throwable;

class JsonException extends \InvalidArgumentException implements Throwable
{
}

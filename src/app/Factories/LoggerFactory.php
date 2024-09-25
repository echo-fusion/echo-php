<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\Container\ServiceManagerInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): LoggerInterface
    {
        $logger = new Logger('app');

        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context%\n",
            null, // Date format
            true, // Allow inline line breaks
            true  // Ignore empty context and extra
        );

        $logHandler = new StreamHandler(STORAGE_PATH . '/logs/app.log', Logger::DEBUG);
        $logHandler->setFormatter($formatter);
        $logger->pushHandler($logHandler);

        return $logger;
    }
}

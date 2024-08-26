<?php

declare(strict_types=1);

namespace App\Components\Response\Html;

use App\Components\Config\ConfigInterface;
use App\Components\Container\ServiceManagerInterface;
use Webmozart\Assert\Assert;

class HtmlResponseFactory
{
    public function __invoke(ServiceManagerInterface $serviceManager): HtmlResponseInterface
    {
        $config = $serviceManager->get(ConfigInterface::class);
        Assert::isInstanceOf($config, ConfigInterface::class);
        $config = $config->getMerged();

        Assert::keyExists($config, 'html_response_agent');
        $agent = $config['html_response_agent'];
        Assert::classExists($agent);

        return $serviceManager->get($agent);
    }
}

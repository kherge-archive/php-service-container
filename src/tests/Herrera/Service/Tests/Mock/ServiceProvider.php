<?php

namespace Herrera\Service\Tests\Mock;

use Herrera\Service\Container;
use Herrera\Service\ProviderInterface;

class ServiceProvider implements ProviderInterface
{
    public $registered;

    public function register(Container $container)
    {
        $this->registered = $container;
    }
}

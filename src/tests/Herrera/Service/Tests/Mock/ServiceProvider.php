<?php

namespace Herrera\Service\Tests\Mock;

use Herrera\Service\Container;
use Herrera\Service\InitializableInterface;
use Herrera\Service\ProviderInterface;

class ServiceProvider implements InitializableInterface, ProviderInterface
{
    public $initialized;
    public $registered;

    public function initialize(Container $container)
    {
        $this->initialized = $container;
    }

    public function register(Container $container)
    {
        $this->registered = $container;
    }
}
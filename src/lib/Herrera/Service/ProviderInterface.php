<?php

namespace Herrera\Service;

/**
 * Defines how a service provider must be implemented.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
interface ProviderInterface
{
    /**
     * Registers the service(s) with the service container.
     *
     * @param Container $container The service container.
     */
    public function register(Container $container);
}
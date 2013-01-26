<?php

namespace Herrera\Service;

/**
 * Indicates that the service provider must be initialized.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
interface InitializableInterface
{
    /**
     * Initializes the service(s) in the service container.
     *
     * @param Container $container The service container.
     */
    public function initialize(Container $container);
}
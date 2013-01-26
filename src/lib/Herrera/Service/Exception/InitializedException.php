<?php

namespace Herrera\Service\Exception;

use LogicException;

/**
 * Used to indicate that the service container is already initialized.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class InitializedException
extends LogicException
implements ExceptionInterface
{
    /**
     * Returns an exception for a service that is already initialized.
     *
     * @return InitializedException The exception.
     */
    public static function create()
    {
        return new self(
            'The service container has already been initialized.'
        );
    }
}
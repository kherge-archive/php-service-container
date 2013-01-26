<?php

namespace Herrera\Service\Exception;

use LogicException;

/**
 * Used to indicate an issue with extending a service.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ExtendException
extends LogicException
implements ExceptionInterface
{
    /**
     * Returns an exception for the non-service being extended.
     *
     * @param string $name The non-service name.
     *
     * @return ExtendException The exception.
     */
    public static function notService($name)
    {
        return new self(sprintf(
            'The value of the key, %s, is not a service.',
            $name
        ));
    }
}
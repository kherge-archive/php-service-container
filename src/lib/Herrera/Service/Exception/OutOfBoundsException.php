<?php

namespace Herrera\Service\Exception;

/**
 * Used to indicate that an invalid key was used.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class OutOfBoundsException
extends \OutOfBoundsException
implements ExceptionInterface
{
    /**
     * Returns an exception for an invalid array key.
     *
     * @param string $key The array key.
     *
     * @return OutOfBoundsException The exception.
     */
    public static function arrayKey($key)
    {
        return new self(sprintf(
            'The array key, %s, is not set.',
            $key
        ));
    }
}
<?php

namespace Herrera\Service\Tests\Exception;

use Herrera\Service\Exception\OutOfBoundsException;
use PHPUnit_Framework_TestCase as TestCase;

class OutOfBoundsExceptionTest extends TestCase
{
    public function testArrayKey()
    {
        $exception = OutOfBoundsException::arrayKey('test');

        $this->assertEquals(
            'The array key, test, is not set.',
            $exception->getMessage()
        );
    }
}
<?php

namespace Herrera\Service\Tests\Exception;

use Herrera\Service\Exception\ExtendException;
use PHPUnit_Framework_TestCase as TestCase;

class ExtendExceptionTest extends TestCase
{
    public function testNotService()
    {
        $exception = ExtendException::notService('test');

        $this->assertEquals(
            'The value of the key, test, is not a service.',
            $exception->getMessage()
        );
    }
}
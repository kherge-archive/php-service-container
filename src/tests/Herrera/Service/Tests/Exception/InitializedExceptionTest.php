<?php

namespace Herrera\Service\Tests\Exception;

use Herrera\Service\Exception\InitializedException;
use PHPUnit_Framework_TestCase as TestCase;

class InitializedExceptionTest extends TestCase
{
    public function testCreate()
    {
        $exception = InitializedException::create();

        $this->assertEquals(
            'The service container has already been initialized.',
            $exception->getMessage()
        );
    }
}
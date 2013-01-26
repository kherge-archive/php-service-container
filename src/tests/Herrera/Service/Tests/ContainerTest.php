<?php

namespace Herrera\Service\Tests;

use Herrera\Service\Container;
use Herrera\Service\Tests\Mock\ServiceProvider;
use PHPUnit_Framework_TestCase as TestCase;

class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    public function testConstructor()
    {
        $expected = array('rand' => rand());
        $container = new Container($expected);

        $this->assertSame($expected['rand'], $container['rand']);
    }

    public function testExtendNotSet()
    {
        $this->setExpectedException(
            'Herrera\\Service\\Exception\\OutOfBoundsException'
        );

        $this->container['test'] = $this->container->extend('test', function () {});
    }

    public function testExtendNotService()
    {
        $this->container['test'] = 123;

        $this->setExpectedException(
            'Herrera\\Service\\Exception\\ExtendException'
        );

        $this->container['test'] = $this->container->extend('test', function () {});
    }

    public function testExtend()
    {
        $this->container['test'] = $this->container->once(function (Container $container) {
            return rand();
        });

        $this->container['test'] = $this->container->extend('test', function (Container $container, $rand) {
            return 'test_' . $rand;
        });

        $this->assertStringStartsWith('test_', $this->container['test']);
        $this->assertEquals($this->container['test'], $this->container['test']);
    }

    public function testInitialized()
    {
        $this->container->initialize();

        $this->setExpectedException(
            'Herrera\\Service\\Exception\\InitializedException'
        );

        $this->container->initialize();
    }

    public function testInitialize()
    {
        $provider = new ServiceProvider();

        $this->container->register($provider);
        $this->container->initialize();

        $this->assertSame($this->container, $provider->initialized);
    }

    public function testMany()
    {
        $callable = function () {};

        $this->container['test'] = $this->container->many($callable);

        $this->assertSame($callable, $this->container['test']);
    }

    public function testOffsetExists()
    {
        $this->assertFalse(isset($this->container['test']));

        $this->container['test'] = 123;

        $this->assertTrue(isset($this->container['test']));
    }

    public function testOffsetGetNotSet()
    {
        $this->setExpectedException(
            'Herrera\\Service\\Exception\\OutOfBoundsException'
        );

        $this->container['test'];
    }

    public function testOffsetGetCallable()
    {
        $this->container['test'] = function () {
            return 123;
        };

        $this->assertSame(123, $this->container['test']);
    }

    public function testOffsetGet()
    {
        $this->container['test'] = 123;

        $this->assertSame(123, $this->container['test']);
    }

    public function testOffsetSet()
    {
        $this->container['test'] = 123;

        $this->assertSame(123, $this->container['test']);
    }

    public function testOffsetUnset()
    {
        $this->container['test'] = 123;

        unset($this->container['test']);

        $this->assertFalse(isset($this->container['test']));
    }

    public function testOnce()
    {
        $this->container['prefix'] = 'test_';
        $this->container['test'] = $this->container->once(function (Container $container) {
            return $container['prefix'] . rand();
        });

        $this->assertEquals($this->container['test'], $this->container['test']);
    }

    public function testRegister()
    {
        $this->container['test'] = array(
            'alpha' => array(
                'beta' => array('gamma')
            ),
            'delta' => 123
        );

        $provider = new ServiceProvider();

        $this->container->register($provider, array(
            'alpha' => array(
                'beta' => array('epsilon'),
                'zeta' => 123
            ),
            'delta' => 456
        ));

        $this->assertSame($this->container, $provider->registered);
        $this->assertEquals(456, $this->container['delta']);
        $this->assertEquals(
            array(
                'beta' => array('epsilon'),
                'zeta' => 123
            ),
            $this->container['alpha']
        );
    }

    protected function setUp()
    {
        $this->container = new Container();
    }
}
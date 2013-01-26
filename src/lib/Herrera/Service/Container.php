<?php

namespace Herrera\Service;

use ArrayAccess;
use Closure;
use Herrera\Service\Exception\ExtendException;
use Herrera\Service\Exception\InitializedException;
use Herrera\Service\Exception\OutOfBoundsException;

/**
 * A simple service container.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class Container implements ArrayAccess
{
    /**
     * The service container contents.
     *
     * @var array
     */
    private $container;

    /**
     * The initialized state of the service container.
     *
     * @var boolean
     */
    private $initialized = false;

    /**
     * The list of services to initialize.
     *
     * @var array
     */
    private $initializable = array();

    /**
     * Initializes the service container.
     *
     * @param array $container The initial service container contents.
     */
    public function __construct(array $container = array())
    {
        $this->container = $container;
    }

    /**
     * Returns a closure that will invoke the service, pass the result to the
     * given callable, and return the result of the callable. Unlike the `once`
     * method, the given callable will be invoked more than once. You may want
     * to consider wrapping the returned closure using `once()`.
     *
     *
     * @param string   $name     The service name.
     * @param callable $callable A callable.
     *
     * @return Closure The closure.
     *
     * @throws ExtendException      If the key's value is not a service.
     * @throws OutOfBoundsException If the name is not used.
     */
    public function extend($name, $callable)
    {
        if (false === array_key_exists($name, $this->container)) {
            throw OutOfBoundsException::arrayKey($name);
        }

        if (false === is_callable($this->container[$name])) {
            throw ExtendException::notService($name);
        }

        $subject = $this->container[$name];

        return function (self $container) use ($callable, $subject) {
            return $callable($container, $subject($container));
        };
    }

    /**
     * Initializes the registered service providers.
     */
    public function initialize()
    {
        if ($this->initialized) {
            throw InitializedException::create();
        }

        foreach ($this->initializable as $provider) {
            $provider->initialize($this);
        }

        $this->initialized = true;
        $this->initializable = array();
    }

    /**
     * Returns a closure that will return the given callable when invoked.
     * This may be used to prevent callables from being treated as services
     * by this service container.
     *
     * @param callable $callable A callable.
     *
     * @return Closure The closure.
     */
    public function many($callable)
    {
        return function () use ($callable) {
            return $callable;
        };
    }

    /**
     * Checks if the service or parameter is set.
     *
     * @param string $offset The service or parameter name.
     *
     * @return boolean TRUE if set, FALSE if not.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->container);
    }

    /**
     * Returns the service or parameter value.
     *
     * @param string $offset The service or parameter name.
     *
     * @return mixed The service or parameter value.
     *
     * @throws OutOfBoundsException If the name is not used.
     */
    public function offsetGet($offset)
    {
        if (false === array_key_exists($offset, $this->container)) {
            throw OutOfBoundsException::arrayKey($offset);
        }

        if (is_callable($this->container[$offset])) {
            return $this->container[$offset]($this);
        }

        return $this->container[$offset];
    }

    /**
     * Sets the service or parameter value.
     *
     * @param string $offset The service or parameter name.
     * @param mixed  $value  The service or parameter value.
     */
    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    /**
     * Unsets the service or parameter.
     *
     * @param string $offset The service or parameter name.
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Returns a closure that will only invoke the given callable once. Each
     * subsequent call will return the same result from the first invocation.
     * The invoked callable will receive this service container as its only
     * parameter.
     *
     * @param callable $callable A callback.
     *
     * @return Closure The closure.
     */
    public function once($callable)
    {
        return function (self $container) use ($callable) {
            static $called = false;
            static $result;

            if (false === $called) {
                $result = $callable($container);
                $called = true;
            }

            return $result;
        };
    }

    /**
     * Registers the service provider with the service container. If the service
     * is initializable (implements the <code>InitializableInterface</code>), it
     * will be initialized when <code>initialize()</code> is called. If any
     * parameters are provided, they will recursively replace or add values to
     * the service container.
     *
     * @param ProviderInterface $provider   The service provider.
     * @param array             $parameters The list of parameters.
     */
    public function register(ProviderInterface $provider, array $parameters = null)
    {
        $provider->register($this);

        if ($provider instanceof InitializableInterface) {
            $this->initializable[] = $provider;
        }

        if (null !== $parameters) {
            $this->container = array_replace_recursive(
                $this->container,
                $parameters
            );
        }
    }
}
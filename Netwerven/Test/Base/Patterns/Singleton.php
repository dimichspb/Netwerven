<?php

namespace Netwerven\Test\Base\Patterns;

use Netwerven\Test\Base\Exceptions\SingletonCloneException;
use Netwerven\Test\Base\Exceptions\SingletonWakeupException;

/**
 * class Singleton.
 * Basic Singleton Design Pattern implementation. Prevents creating multiply instances of an object
 *
 */
trait Singleton
{
    /**
     * @var Singleton reference to singleton instance
     */
    private static $instance;

    /**
     * Gets the instance via lazy initialization (created on first usage).
     *
     * @return self
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Is not allowed to call from outside: private!
     */
    private function __construct()
    {
    }

    /**
     * Prevent the instance from being cloned.
     *
     * @throws SingletonCloneException
     *
     * @return void
     */
    final public function __clone()
    {
        throw new SingletonCloneException();
    }

    /**
     * Prevent from being unserialized.
     *
     * @throws SingletonWakeupException
     *
     * @return void
     */
    final public function __wakeup()
    {
        throw new SingletonWakeupException('This is a Singleton. __wakeup usage is forbidden');
    }
}
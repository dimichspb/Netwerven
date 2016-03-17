<?php

namespace Netwerven\Test\Base\Patterns;

use Netwerven\Test\Base\Exceptions\SingletonCloneException;
use Netwerven\Test\Base\Exceptions\SingletonWakeupException;

/**
 * class Singleton.
 */
trait Singleton
{
    /**
     * @var Singleton reference to singleton instance
     */
    private static $instance;

    /**
     * gets the instance via lazy initialization (created on first usage).
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
     * is not allowed to call from outside: private!
     */
    private function __construct()
    {
    }

    /**
     * prevent the instance from being cloned.
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
     * prevent from being unserialized.
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
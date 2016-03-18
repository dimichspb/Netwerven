<?php
namespace Netwerven\Test\Base\Exceptions;

/**
 * Class SingletonWakeupException
 * @package Netwerven\Test\Base\Exceptions
 */
class SingletonWakeupException extends BaseException {

    /**
     * @var string
     */
    protected $message = 'This is a Singleton. __wakeup usage is forbidden';

}
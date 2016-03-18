<?php
namespace Netwerven\Test\Base\Exceptions;

/**
 * Class SingletonCloneException
 * @package Netwerven\Test\Base\Exceptions
 */
class SingletonCloneException extends BaseException {

    /**
     * @var string
     */
    protected $defaultMessage = 'This is a Singleton. Clone is forbidden';

}
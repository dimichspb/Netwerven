<?php
namespace Netwerven\Test\Base\Exceptions;

/**
 * Class InvalidArgumentException
 * @package Netwerven\Test\Base\Exceptions
 */
class InvalidArgumentException extends BaseInvalidArgumentException {

    /**
     * @var string
     */
    protected $defaultMessage = 'Default invalid argument exception';

}
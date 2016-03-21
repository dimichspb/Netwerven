<?php
namespace Netwerven\Test\DataSources\Exceptions;

/**
 * Class ConnectException
 * @package Netwerven\Test\DataSources\Exceptions
 */
class ConnectException extends MySQLiException {

    /**
     * @var string
     */
    protected $defaultMessage = 'Connection error exception';
}
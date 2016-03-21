<?php
namespace Netwerven\Test\DataSources\Exceptions;

/**
 * Class DataException
 * @package Netwerven\Test\DataSources\Exceptions
 */
class DataException extends MySQLiException {
    /**
     * @var string
     */
    protected $defaultMessage = 'Wrong data exception';

}
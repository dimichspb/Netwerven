<?php
namespace Netwerven\Test\DataSources\Exceptions;

/**
 * Class QueryException
 * @package Netwerven\Test\DataSources\Exceptions
 */
class QueryException extends MySQLiException {

    /**
     * @var string
     */
    protected $defaultMessage = 'Query error exception';

}
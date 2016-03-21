<?php
namespace Netwerven\Test\DataSources\Exceptions;

/**
 * Class MySQLiException
 * @package Netwerven\Test\DataSources\Exceptions
 */
class MySQLiException extends \mysqli_sql_exception {
    /**
     * @var string
     */
    protected $defaultMessage = 'MySQLi exception';

}
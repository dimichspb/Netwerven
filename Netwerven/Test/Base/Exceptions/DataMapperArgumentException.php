<?php
namespace Netwerven\Test\Base\Exceptions;

/**
 * Class DataMapperArgumentException
 * @package Netwerven\Test\Base\Exceptions
 */
class DataMapperArgumentException extends BaseException {

    /**
     * @var string
     */
    protected $defaultMessage = 'This is a Data mapper. Invalid data argument';

}
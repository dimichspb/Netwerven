<?php
namespace Netwerven\Test\DataSources\Exceptions;

use Netwerven\Test\Base\Exceptions\BaseInvalidArgumentException;

/**
 * Class InvalidConfigArgumentException
 * @package Netwerven\Test\DataSources\Exceptions
 */
class InvalidConfigArgumentException extends BaseInvalidArgumentException {

    /**
     * @var string
     */
    protected $defaultMessage = 'Invalid config argument exception';

}
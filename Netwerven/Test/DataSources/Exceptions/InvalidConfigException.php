<?php
namespace Netwerven\Test\DataSources\Exceptions;

use Netwerven\Test\Base\Exceptions\BaseInvalidArgumentException;

/**
 * Class InvalidConfigException
 * @package Netwerven\Test\DataSources\Exceptions
 */
class InvalidConfigException extends BaseInvalidArgumentException {

    /**
     * @var string
     */
    protected $defaultMessage = 'Invalid config';

}
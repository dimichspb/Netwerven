<?php
namespace Netwerven\Test\Repositories\Exceptions;

use Netwerven\Test\Base\Exceptions\BaseInvalidArgumentException;

/**
 * Class InvalidRepositoryArgumentException
 * @package Netwerven\Test\Repositories\Exceptions
 */
class InvalidRepositoryArgumentException extends BaseInvalidArgumentException {

    /**
     * @var string
     */
    protected $defaultMessage = 'Invalid repository argument';

}
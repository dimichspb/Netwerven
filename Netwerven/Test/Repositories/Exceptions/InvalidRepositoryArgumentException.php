<?php
namespace Netwerven\Test\Repositories\Exceptions;

use Netwerven\Test\Base\Exceptions\BaseInvalidArgumentException;

class InvalidRepositoryArgumentException extends BaseInvalidArgumentException {

    protected $defaultMessage = 'Invalid repository argument';

}
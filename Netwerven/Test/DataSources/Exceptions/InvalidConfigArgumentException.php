<?php
namespace Netwerven\Test\DataSources\Exceptions;

use Netwerven\Test\Base\Exceptions\BaseInvalidArgumentException;

class InvalidConfigArgumentException extends BaseInvalidArgumentException {

    protected $defaultMessage = 'Invalid config argument exception';

}
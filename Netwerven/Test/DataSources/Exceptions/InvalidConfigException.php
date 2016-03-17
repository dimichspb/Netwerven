<?php
namespace Netwerven\Test\DataSources\Exceptions;

use Netwerven\Test\Base\Exceptions\BaseInvalidArgumentException;

class InvalidConfigException extends BaseInvalidArgumentException {

    protected $defaultMessage = 'Invalid config';

}
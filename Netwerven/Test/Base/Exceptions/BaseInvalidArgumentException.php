<?php
namespace Netwerven\Test\Base\Exceptions;

abstract class BaseInvalidArgumentException extends \InvalidArgumentException {

    protected $defaultMessage = 'Invalid argument Exception';

    public function __construct($message = '', $code = 0, BaseException $previous = null)
    {
        parent::__construct(!empty($message)? $message: $this->defaultMessage, $code, $previous);
    }

}
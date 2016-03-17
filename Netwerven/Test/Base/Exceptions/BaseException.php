<?php
namespace Netwerven\Test\Base\Exceptions;

abstract class BaseException extends \Exception {

    protected $defaultMessage = 'Base exception';

    public function __construct($message = '', $code = 0, BaseException $previous = null)
    {
        parent::__construct(!empty($message)? $message: $this->defaultMessage, $code, $previous);
    }

}
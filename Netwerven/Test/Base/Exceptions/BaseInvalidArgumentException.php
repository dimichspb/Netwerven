<?php
namespace Netwerven\Test\Base\Exceptions;

/**
 * Class BaseInvalidArgumentException
 * @package Netwerven\Test\Base\Exceptions
 */
abstract class BaseInvalidArgumentException extends \InvalidArgumentException {

    /**
     * @var string
     */
    protected $defaultMessage = 'Invalid argument Exception';

    /**
     * BaseInvalidArgumentException constructor.
     * @param string $message
     * @param int $code
     * @param BaseException|null $previous
     */
    public function __construct($message = '', $code = 0, BaseException $previous = null)
    {
        parent::__construct(!empty($message)? $message: $this->defaultMessage, $code, $previous);
    }

    /**
     * @return string
     */
    public function getDefaultMessage()
    {
        return $this->defaultMessage;
    }

    /**
     * @param string $defaultMessage
     */
    public function setDefaultMessage($defaultMessage)
    {
        $this->defaultMessage = $defaultMessage;
    }


}
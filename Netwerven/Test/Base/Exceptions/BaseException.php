<?php
namespace Netwerven\Test\Base\Exceptions;

/**
 * Class BaseException
 * @package Netwerven\Test\Base\Exceptions
 */
abstract class BaseException extends \Exception {

    /**
     * @var string
     */
    protected $defaultMessage = 'Base exception';

    /**
     * BaseException constructor.
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
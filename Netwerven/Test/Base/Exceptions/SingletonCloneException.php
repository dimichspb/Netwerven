<?php
namespace Netwerven\Test\Base\Exceptions;

class SingletonCloneException extends BaseException {

    protected $defaultMessage = 'This is a Singleton. Clone is forbidden';

}
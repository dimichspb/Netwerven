<?php
namespace Netwerven\Test\Base\Exceptions;

class SingletonWakeupException extends BaseException {

    protected $message = 'This is a Singleton. __wakeup usage is forbidden';

}
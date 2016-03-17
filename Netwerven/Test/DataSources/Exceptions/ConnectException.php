<?php
namespace Netwerven\Test\DataSources\Exceptions;

class ConnectException extends InvalidConfigArgumentException {

    protected $defaultMessage = 'Error connecting to data source';

}
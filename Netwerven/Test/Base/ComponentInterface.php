<?php
namespace Netwerven\Test\Base;

interface ComponentInterface {

    const NAMESPACE_SEPARATOR = '\\';

    public function className();

    public static function getNamespace();

}
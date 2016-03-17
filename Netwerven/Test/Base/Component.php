<?php
namespace Netwerven\Test\Base;

abstract class Component implements ComponentInterface {

    public function className()
    {
        return get_class($this);
    }

    public static function getClassName()
    {
        return static::class;
    }

    public static function getShortClassName()
    {
        $reflector = new \ReflectionClass(static::getClassName());
        return $reflector->getShortName();
    }

    public static function getNamespace()
    {
        $reflector = new \ReflectionClass(static::getClassName());
        return $reflector->getNamespaceName();
    }

    public function asArray()
    {
        return get_object_vars($this);
    }
}
<?php
namespace Netwerven\Test\Base;

/**
 * Class Component
 * @package Netwerven\Test\Base
 */
abstract class Component implements ComponentInterface {

    /**
     * @return string
     */
    public function className()
    {
        return get_class($this);
    }

    /**
     * @return mixed
     */
    public static function getClassName()
    {
        return static::class;
    }

    /**
     * @return string
     */
    public static function getShortClassName()
    {
        $reflector = new \ReflectionClass(static::getClassName());
        return $reflector->getShortName();
    }

    /**
     * @return string
     */
    public static function getNamespace()
    {
        $reflector = new \ReflectionClass(static::getClassName());
        return $reflector->getNamespaceName();
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return get_object_vars($this);
    }
}
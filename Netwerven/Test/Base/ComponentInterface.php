<?php
namespace Netwerven\Test\Base;

/**
 * Interface ComponentInterface
 * @package Netwerven\Test\Base
 */
interface ComponentInterface {

    /**
     *
     */
    const NAMESPACE_SEPARATOR = '\\';

    /**
     * @return mixed
     */
    public function className();

    /**
     * @return mixed
     */
    public static function getNamespace();

    public function stdout($message);

}
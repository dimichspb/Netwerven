<?php
namespace Netwerven\Test\DataSources;

/**
 * Interface DataSourceConfigInterface
 * @package Netwerven\Test\DataSources
 */
interface DataSourceConfigInterface {

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return mixed
     */
    public function set($attributeName, $attributeValue);

    /**
     * @param $attributeName
     * @return mixed
     */
    public function get($attributeName);

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return mixed
     */
    public function __set($attributeName, $attributeValue);

    /**
     * @param $attributeName
     * @return mixed
     */
    public function __get($attributeName);

}
<?php
namespace Netwerven\Test\DataSources;

/**
 * Interface DataSourceConfigInterface
 * Describes public methods which all DataSourceConfigs mush have
 *
 * @package Netwerven\Test\DataSources
 */
interface DataSourceConfigInterface {

    /**
     * Sets the value of the attribute
     *
     * @param $attributeName
     * @param $attributeValue
     * @return mixed
     */
    public function set($attributeName, $attributeValue);

    /**
     * Returns the value of the attribute
     *
     * @param $attributeName
     * @return mixed
     */
    public function get($attributeName);

}
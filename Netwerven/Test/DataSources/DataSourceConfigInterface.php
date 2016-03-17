<?php
namespace Netwerven\Test\DataSources;

interface DataSourceConfigInterface {

    public function __construct(array $configArray = []);

    public function set($attributeName, $attributeValue);

    public function get($attributeName);

    public function __set($attributeName, $attributeValue);

    public function __get($attributeName);

}
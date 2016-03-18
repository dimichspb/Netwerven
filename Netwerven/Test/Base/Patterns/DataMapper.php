<?php
namespace Netwerven\Test\Base\Patterns;

use Netwerven\Test\Base\Exceptions\DataMapperArgumentException;
use Netwerven\Test\Base\Exceptions\InvalidArgumentException;

/**
 * Class DataMapper
 * @package Netwerven\Test\Base\Patterns
 */
trait DataMapper {

    /**
     * DataMapper constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    protected function setAttributes(array $attributes)
    {
        foreach($attributes as $attributeName => $attributeValue) {
            try {
                $this->set($attributeName, $attributeValue);
            } catch(\Exception $e) {
                echo $e->getMessage(), PHP_EOL, "name: ", $attributeName, ", value: ", $attributeValue, PHP_EOL;
            }
        }
    }

    public function checkAttributes(array $attributes = [])
    {
        $classAttributes = array_keys(get_class_vars(get_class($this)));
        $attributesKeys = count($attributes)?
            array_keys($attributes):
            array_keys(array_filter(get_object_vars($this), function($element) {
                return !is_null($element);
            }));

        return $classAttributes === $attributesKeys;
    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return mixed
     */
    public function set($attributeName, $attributeValue) {
        if (!is_string($attributeName)) {
            throw new InvalidArgumentException('Attribute name must be a string');
        }
        return $this->$attributeName = $attributeValue;
    }

    /**
     * @param $attributeName
     * @return string|array|object
     */
    public function get($attributeName) {
        if (!is_string($attributeName)) {
            throw new InvalidArgumentException('Attribute name must be a string');
        }
        return $this->$attributeName;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        throw new InvalidArgumentException('Attribute ' . $name . ' is not defined in ' . $this->className());
    }

    /**
     * @param $name
     */
    public function __get($name)
    {
        throw new InvalidArgumentException('Attribute ' . $name . ' is not defined ' . $this->className());
    }
}
<?php
namespace Netwerven\Test\Base\Patterns;

use Netwerven\Test\Base\Exceptions\DataMapperInvalidArgumentException;

/**
 * Class DataMapper
 * Implementation of DataMapper design pattern. Releases default setter and getter methods.
 * Prevents access to unspecified attributes. Provides checkAttributes method to whether all necessary attributes are
 * specified or not
 *
 * @package Netwerven\Test\Base\Patterns
 */
trait DataMapper {

    /**
     * DataMapper constructor.
     * Sets attributes of the object
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * Sets attributes
     *
     * @param array $attributes
     */
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

    /**
     * Checks whether all attributes are specified or not
     *
     * @param array $attributes
     * @return bool
     */
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
     * Setter of specified attribute
     *
     * @param $attributeName
     * @param $attributeValue
     * @return mixed
     */
    public function set($attributeName, $attributeValue) {
        if (!is_string($attributeName)) {
            throw new DataMapperInvalidArgumentException('Attribute name must be a string');
        }
        return $this->$attributeName = $attributeValue;
    }

    /**
     * Getter of specified attribute
     *
     * @param $attributeName
     * @return string|array|object
     */
    public function get($attributeName) {
        if (!is_string($attributeName)) {
            throw new DataMapperInvalidArgumentException('Attribute name must be a string');
        }
        return $this->$attributeName;
    }

    /**
     * Magic method prevents access to unspecified attributes
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        throw new DataMapperInvalidArgumentException('Attribute ' . $name . ' is not defined in ' . get_class($this));
    }

    /**
     * Magic method prevents access to unspecified attributes
     *
     * @param $name
     */
    public function __get($name)
    {
        throw new DataMapperInvalidArgumentException('Attribute ' . $name . ' is not defined ' . get_class($this));
    }
}
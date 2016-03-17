<?php
namespace Netwerven\Test\Base\Patterns;

use Netwerven\Test\Base\Exceptions\InvalidArgumentException;

trait DataMapper {

    public function __construct(array $attributes = [])
    {
        foreach($attributes as $attributeName => $attributeValue) {
            try {
                $this->set($attributeName, $attributeValue);
            } catch(\Exception $e) {
                echo $e->getMessage(), PHP_EOL, "name: ", $attributeName, ", value: ", $attributeValue, PHP_EOL;
            }
        }
    }

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

    public function __set($name, $value)
    {
        throw new InvalidArgumentException('Attribute ' . $name . ' is not defined in ' . $this->className());
    }

    public function __get($name)
    {
        throw new InvalidArgumentException('Attribute ' . $name . ' is not defined ' . $this->className());
    }
}
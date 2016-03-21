<?php
namespace Netwerven\Test\Models;

/**
 * Interface ModelInterface
 *
 * Describes public methods of all models which they must have
 *
 * @package Netwerven\Test\Models
 */
interface ModelInterface {

    /**
     * Provides access to the Model attributes as array
     *
     * @return mixed
     */
    public function asArray();
}
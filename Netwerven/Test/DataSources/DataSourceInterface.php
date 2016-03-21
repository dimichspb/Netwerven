<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Models\Model;

/**
 * Interface DataSourceInterface
 * Describes public methods of all DataSources which they must have
 *
 * @package Netwerven\Test\DataSources
 */
interface DataSourceInterface {

    /**
     * Returns all models from the particular DataSource
     *
     * @return Model[]
     */
    public function all(Model $model);

    /**
     * Returns all models from the particular DataSource which are similar to the provided one
     *
     * @param Model $model
     * @return Model[]
     */
    public function filter(Model $model);

    /**
     * Returns first (or with specified index) Model from the particular DataSource which is similar to the provided one
     *
     * @param int $index
     * @param Model $model
     * @return Model[]
     */
    public function one($index = 0, Model $model);

    /**
     * Adds model to the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    public function add(Model $model);

    /**
     * Updates the model at the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    public function update(Model $model);

    /**
     * Deletes the model from the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    public function delete(Model $model);

    /**
     * Check whether the DataSource can be active or not
     *
     * @return boolean
     */
    public function canBeActive();


}
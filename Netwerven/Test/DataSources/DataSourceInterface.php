<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Models\Model;

/**
 * Interface DataSourceInterface
 * @package Netwerven\Test\DataSources
 */
interface DataSourceInterface {

    /**
     * @param Model $model
     * @return mixed
     */
    public function all(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function filter(Model $model);

    /**
     * @param int $index
     * @param Model $model
     * @return mixed
     */
    public function one($index = 0, Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function add(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function update(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function delete(Model $model);

    /**
     * @return mixed
     */
    public function canBeActive();

}
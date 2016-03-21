<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\ModelContainer;

/**
 * Class DataSource
 *
 *
 * @package Netwerven\Test\DataSources
 */
abstract class DataSource extends Component implements DataSourceInterface {

    /**
     * Object of DataSourceConfig class
     *
     * @var DataSourceConfig
     */
    protected $config;

    /**
     * Returns mapping "Model class" => "Table name"
     *
     * @param string $modelName
     * @return string|array|null
     */
    protected function getMapping($modelName = '')
    {
        if (!isset($this->config->mapping)) {
            return [];
        }
        if (is_string($modelName)) {
            return isset($this->config->mapping[$modelName]) ? $this->config->mapping[$modelName] : null;
        }
        return $this->config->mapping;
    }

    /**
     * Returns all models from the particular DataSource
     *
     * @param Model $model
     * @return ModelContainer[]
     */
    public function all(Model $model)
    {
        $this->filter($model);
    }

    /**
     * Returns all models from the particular DataSource which are similar to the provided one
     *
     * @param Model $model
     * @return ModelContainer[]
     */
    abstract function filter(Model $model);

    /**
     * Adds model to the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    abstract function add(Model $model);

    /**
     * Updates the model at the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    abstract function update(Model $model);

    /**
     * Deletes the model from the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    abstract function delete(Model $model);

    /**
     * Check whether the DataSource can be active or not
     *
     * @return boolean
     */
    abstract function canBeActive();

    /**
     * Returns first (or with specified index) Model from the particular DataSource which is similar to the provided one
     *
     * @param int $index
     * @param Model $model
     * @return Model
     */
    public function one($index = 0, Model $model)
    {
        $allModels = static::filter($model);
        if (!is_string($index)) {
            while ($index-- > 0) next($allModels);
            $result = current($allModels);
        } else {
            $allModels = (object)$allModels;
            $result = (array) (isset($allModels->$index)? $allModels->$index: []);
        }
        return $result;
    }
}
<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Models\Model;

/**
 * Class DataSource
 * @package Netwerven\Test\DataSources
 */
abstract class DataSource extends Component implements DataSourceInterface {

    /**
     * @var
     */
    protected $config;

    /**
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
     * @param Model $model
     */
    public function all(Model $model)
    {
        static::filter($model);
    }

    /**
     * @param Model $model
     * @return mixed
     */
    abstract function filter(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    abstract function add(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    abstract function update(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    abstract function delete(Model $model);

    /**
     * @param int $index
     * @param Model $model
     * @return array|mixed
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
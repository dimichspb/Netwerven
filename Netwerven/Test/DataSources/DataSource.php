<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Base\Component;

abstract class DataSource extends Component implements DataSourceInterface {

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
}
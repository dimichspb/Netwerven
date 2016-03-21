<?php
namespace Netwerven\Test\DataSources;

/**
 * Class JsonConfig
 * Example class to confugure JsonDataSource
 *
 * @package Netwerven\Test\DataSources
 */
class JsonConfig extends DataSourceConfig {

    /**
     * Must have file path specified
     *
     * @var
     */
    protected $filePath;

    /**
     * Must have primary key specified
     *
     * @var
     */
    protected $primaryKey;

    /**
     * JsonConfig constructor.
     * Check the specified params and sets attributes if count the same
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        if (!$this->checkAttributes($attributes)) {
            $this->stdout('Can\'t create Json config. Not enough params');
        }
        parent::__construct($attributes);
    }
}
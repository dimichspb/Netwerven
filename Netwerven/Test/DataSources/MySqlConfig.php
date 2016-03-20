<?php
namespace Netwerven\Test\DataSources;

/**
 * Class MySqlConfig
 * @package Netwerven\Test\DataSources
 */
class MySqlConfig extends DataSourceConfig{
    /**
     * @var
     */
    protected $dbhost;
    /**
     * @var
     */
    protected $dbname;
    /**
     * @var
     */
    protected $dbuser;
    /**
     * @var
     */
    protected $dbpass;

    public function __construct(array $attributes)
    {
        if (!$this->checkAttributes($attributes)) {
            $this->stdout('Can\'t create MySQL config. Not enough params');
        }
        parent::__construct($attributes);
    }
}
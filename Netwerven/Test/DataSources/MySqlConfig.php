<?php
namespace Netwerven\Test\DataSources;

/**
 * Class MySqlConfig
 * Example MySQL database DataSource config class
 *
 * @package Netwerven\Test\DataSources
 */
class MySqlConfig extends DataSourceConfig{

    /**
     * Host of MySQL database
     *
     * @var
     */
    protected $dbhost;

    /**
     * Name of MySQL database
     *
     * @var
     */
    protected $dbname;

    /**
     * User name of MySQL database
     *
     * @var
     */
    protected $dbuser;

    /**
     * User password of MySQL database
     *
     * @var
     */
    protected $dbpass;


    /**
     * MySqlConfig constructor.
     * Notice if missing params
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        if (!$this->checkAttributes($attributes)) {
            $this->stdout('Can\'t create MySQL config. Not enough params');
        }
        parent::__construct($attributes);
    }
}
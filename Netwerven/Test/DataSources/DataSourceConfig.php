<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\DataMapper;
use Netwerven\Test\DataSources\Exceptions\InvalidConfigArgumentException;

/**
 * Class DataSourceConfig
 * @package Netwerven\Test\DataSources
 */
abstract class DataSourceConfig extends Component implements DataSourceConfigInterface {

    use DataMapper;

    /**
     * @var array
     */
    public $mapping = [];

    public function __construct(array $attributes)
    {
        $this->setAttributes($attributes);
    }
}
<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\DataMapper;

abstract class DataSourceConfig extends Component implements DataSourceConfigInterface {

    use DataMapper;

    public $mapping = [];

}
<?php
namespace Netwerven\Test\Models;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\DataMapper;

abstract class Model extends Component implements ModelInterface {

    use DataMapper;

    public $dataSource;

}
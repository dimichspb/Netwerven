<?php
namespace Netwerven\Test\Models;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\DataMapper;

/**
 * Class Model
 * @package Netwerven\Test\Models
 */
abstract class Model extends Component implements ModelInterface {

    use DataMapper;

}
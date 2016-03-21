<?php
namespace Netwerven\Test\Models;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\DataMapper;

/**
 * Class Model
 * Base class of all models
 *
 * @package Netwerven\Test\Models
 */
abstract class Model extends Component implements ModelInterface {

    // Model should only have attributes specified in particular class. So it must be DataMapper
    use DataMapper;

}
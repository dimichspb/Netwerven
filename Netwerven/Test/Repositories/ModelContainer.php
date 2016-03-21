<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\DataMapper;
use Netwerven\Test\Models\Model;
use Netwerven\Test\DataSources\DataSource;

/**
 * Class ModelContainer
 *
 * Contains Model and DataSource which the Model belongs to
 *
 * @package Netwerven\Test\Repositories
 */
class ModelContainer extends Component {

    // ModelContainer should not have only other attributes so it must be DataMapper
    use DataMapper;

    /**
     * An object of Model class
     *
     * @var Model
     */
    public $model;

    /**
     * An object of DataSource class which the Model object belongs to
     *
     * @var DataSource
     */
    public $dataSource;

    /**
     * ModelContainer constructor.
     * Sets Model and DataSource
     *
     * @param Model $model
     * @param DataSource $dataSource
     */
    public function __construct(Model $model, DataSource $dataSource)
    {
        $this->model = $model;
        $this->dataSource = $dataSource;
    }

}
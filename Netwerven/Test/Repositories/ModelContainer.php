<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\DataMapper;
use Netwerven\Test\Models\Model;
use Netwerven\Test\DataSources\DataSource;

/**
 * Class ModelContainer
 * @package Netwerven\Test\Repositories
 */
class ModelContainer extends Component {

    use DataMapper;

    /**
     * @var Model
     */
    public $model;
    /**
     * @var DataSource
     */
    public $dataSource;

    /**
     * ModelContainer constructor.
     * @param Model $model
     * @param DataSource $dataSource
     */
    public function __construct(Model $model, DataSource $dataSource)
    {
        $this->model = $model;
        $this->dataSource = $dataSource;
    }

}
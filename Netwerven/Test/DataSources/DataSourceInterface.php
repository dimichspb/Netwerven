<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Models\Model;

interface DataSourceInterface {

    public function all(Model $model);

}
<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\Singleton;
use Netwerven\Test\DataSources\DataSource;
use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\Exceptions\InvalidRepositoryArgumentException;

/**
 * Class Repository
 *
 * Abstract class contains common method to work with data repositories
 *
 * @package Netwerven\Test\Repositories
 */
abstract class Repository extends Component implements RepositoryInterface {

    // Each repository can be created only once thus it must be Singleton
    use Singleton;

    /**
     * Describes the class of model the repository contains
     *
     * @var
     */
    protected static $modelClass;

    /**
     * Configures the name of the field which contains Primary Key for add/update/delete methods
     *
     * @var
     */
    protected static $keyField;

    /**
     * Contains last results of the filter method
     *
     * @var array
     */
    protected static $lastResults = [];

    /**
     * Contains an array of all data sources in use of particular repository
     *
     * @var array
     */
    protected static $dataSources = [];

    /**
     * Sets the particular repository should use the provided DataSource with specified alias
     *
     * @param $alias
     * @param DataSource $dataSource
     * @return boolean  If DataSource has been add to the repository successfully
     */
    public static function using($alias, DataSource $dataSource)
    {
        try {
            return self::addToDataSourcesArray($alias, $dataSource);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). ' alias: '. $alias. '. value: '. serialize($dataSource));
        }
    }

    /**
     * Returns DataSource with specified alias which in use of the repository
     *
     * @param $alias
     * @return mixed|void
     */
    public static function source($alias) {
        try {
            return self::getFromDataSourcesArray($alias);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). 'alias: '. $alias);
        }
    }

    /**
     * Sets the repository should not use the DataSource with specified $alias anymore
     *
     * @param $alias
     * @return mixed|void
     */
    public static function unusing($alias)
    {
        try {
            self::removeFromDataSourcesArray($alias);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). 'alias: '. $alias);
        }
    }

    /**
     * Sets the DataSource with specified alias active
     *
     * @param $alias
     * @return mixed|void
     */
    public static function activate($alias)
    {
        try {
            self::setDataSourceActive($alias);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). 'alias: '. $alias);
        }
    }

    /**
     * Sets the DataSource with the specified alias as inactive
     *
     * @param $alias
     * @return mixed|void
     */
    public static function deactivate($alias)
    {
        try {
            self::setDataSourceInactive($alias);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). 'alias: '. $alias);
        }
    }

    /**
     * Checks whether the DataSource with specified alias is active or not
     *
     * @param $alias
     * @return bool
     */
    public static function isActive($alias)
    {
        try {
            return self::isDataSourceActive($alias);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). 'alias: '. $alias);
            return false;
        }
    }

    /**
     * Adds DataSource with specified alias as element to $dataSources array
     *
     * @param $alias
     * @param DataSource $dataSource
     * @return boolean  If added successfully
     */
    private static function addToDataSourcesArray($alias, DataSource $dataSource)
    {
        if (!self::isString($alias)) {
            return false;
        }
        static::$dataSources[$alias] = [
            'active' => $dataSource->canBeActive(),
            'source' => $dataSource,
        ];
        return true;
    }

    /**
     * Returns the $dataSources element with the specified alias
     *
     * @param $alias
     * @return DataSource
     */
    private static function getFromDataSourcesArray($alias)
    {
        if (self::isUsing($alias)) {
            return static::$dataSources[$alias]['source'];
        }
    }

    /**
     * Unsets the element of $dataSources array with the specified alias
     *
     * @param $alias
     */
    private static function removeFromDataSourcesArray($alias)
    {
        if (self::isUsing($alias)) {
            unset(static::$dataSources[$alias]);
        }
    }

    /**
     * Checks whether the DataSource with the specified alias can be active and sets it active if true
     *
     * @param $alias
     */
    private static function setDataSourceActive($alias)
    {
        if (static::isUsing($alias)) {
            $dataSource = static::getDataSource($alias);
            static::$dataSources[$alias]['active'] = $dataSource->canBeActive()? true: false;
        }
    }

    /**
     * Sets the DataSource inactive in $dataSources array
     *
     * @param $alias
     */
    private static function setDataSourceInactive($alias)
    {
        if (static::isUsing($alias)) {
            static::$dataSources[$alias]['active'] = false;
        }
    }

    /**
     * Checks whether the active attribute of $dataSources array with the specified alias is true or not
     *
     * @param $alias
     * @return bool
     */
    private static function isDataSourceActive($alias)
    {
        if (static::isUsing($alias)) {
            return static::$dataSources[$alias]['active'] === true;
        } else {
            return false;
        }

    }

    /**
     * Returns an element of $dataSources array with the specified alias
     *
     * @param $alias
     * @return DataSource
     */
    private static function getDataSource($alias)
    {
        if (static::isUsing($alias)) {
            return static::$dataSources[$alias]['source'];
        }
    }

    /**
     * Check whether the repository uses the DataSource with the specified alias
     *
     * @param $alias
     * @return bool
     */
    public static function isUsing($alias)
    {
        if (self::isString($alias)) {
            return isset(static::$dataSources[$alias]);
        }
    }

    /**
     * Check whether the specified alias is string or not
     *
     * @param $alias
     * @throws InvalidRepositoryArgumentException when the alias is not string
     * @return bool
     */
    private static function isString($alias)
    {
        if (!is_string($alias)) {
            throw new InvalidRepositoryArgumentException('Alias name must be a string');
        }
        return true;
    }

    /**
     * Returns the array of all DataSources in use
     *
     * @return DataSource[]
     */
    public static function sources()
    {
        $activeSources = array_filter(static::$dataSources, function($dataSource) {
            return $dataSource['active'] === true;
        });
        return array_map(function($element) {
            return $element['source'];
        }, $activeSources);
    }

    /**
     * Returns the array of ModelContainers
     *
     * @return ModelContainer[]
     */
    public static function all()
    {
        return static::filter([]);
    }

    /**
     * Adds the specified $model to all DataSources of the particular repository
     *
     * TODO:: filter DataSources with alias to add only to the specified DataSource
     *
     * @param Model $model
     * @param string $alias
     * @return bool
     */
    public static function add(Model $model, $alias = '')
    {
        $sources = static::sources();
        $count = count($sources);
        foreach ($sources as $source) {
            if ($source->add($model)) $count--;
        }
        return $count === 0;
    }

    /**
     * Updates the specified $model at all DataSources of the particular repository
     *
     * TODO:: filter DataSources with alias to update only at the specified DataSource
     *
     * @param Model $model
     * @param string $alias
     * @return bool
     */
    public static function update(Model $model, $alias = '')
    {
        $sources = static::sources();
        $count = count($sources);
        foreach ($sources as $source) {
            if ($source->update($model)) $count--;
        }
        return $count === 0;
    }

    /**
     * Deletes the specified $model at all DataSources of the particular repository
     *
     * TODO:: filter DataSources with alias to delete only from the specified DataSource
     *
     * @param Model $model
     * @param string $alias
     * @return bool
     */
    public static function delete(Model $model, $alias = '')
    {
        $sources = static::sources();
        $count = count($sources);
        foreach ($sources as $source) {
            if ($source->delete($model)) $count--;
        }
        return $count === 0;
    }

    /**
     * Returns an array of ModelContainers which contain only the models similar to specified $filter array.
     * Groups the results by key field
     * It is possible to specify alias of the DataSource where to search for the models.
     *
     * @param array $filter
     * @param string $alias
     * @return ModelContainer[]
     */
    public static function filter(array $filter, $alias = '')
    {
        static::$lastResults = [];
        $model = static::newModel($filter);
        if (!empty($alias) && $source = self::getFromDataSourcesArray($alias)) {
            self::mergeResults($source->filter($model));
        } else {
            $sources = static::sources();
            foreach ($sources as $source) {
                self::mergeResults($source->filter($model));
            }
        }
        self::groupByField();
        return static::$lastResults;
    }

    /**
     * Returns the array of models with the specified key field value ($index) from the particular repository filtered by $filter array.
     * It is possible to specify alias of the DataSource where to search for the models.
     *
     * @param int|string $index
     * @param array $filter
     * @param string $alias
     * @return array|mixed
     */
    public static function find($index = 0, array $filter = [], $alias = '')
    {
        $allModels = static::filter($filter, $alias);
        switch (gettype($index)) {
            case 'string':
                $allModels = (object)$allModels;        // array to object conversion because of convert strings to integer
                return (array) (isset($allModels->$index)? $allModels->$index: []);
                break;
            case 'integer':
                while ($index-- > 0) next($allModels);
                return current($allModels);
                break;
            default:
        }
    }

    /**
     * Returns first ModelContainer which contains the model with the specified value of key field ($index).
     * It is possible to specify the alias of the DataSource where to search for the model
     *
     * @param $index
     * @param $alias
     * @return mixed
     */
    public static function one($index, $alias)
    {
        if ($modelContainers = static::find($index, [], $alias)) {
            return $modelContainers[0];
        }
    }

    /**
     * Merging $modelContainers array to $lastResults array
     *
     * @param array $modelContainers
     * @return ModelContainer[]
     */
    private static function mergeResults(array $modelContainers)
    {
        return static::$lastResults = array_merge(static::$lastResults, $modelContainers);
    }

    /**
     * Groups results by the specified field or by key field if nothing specified
     *
     * @param string $fieldName
     * @return ModelContainer[]
     */
    private static function groupByField($fieldName = '')
    {
        $fieldName = !empty($fieldName)? $fieldName: static::$keyField;
        if (!empty($fieldName)) {
            $group = new \stdClass();
            foreach (static::$lastResults as $modelContainer) {
                if (!isset($modelContainer->model->$fieldName)) continue;
                $key = $modelContainer->model->$fieldName;
                $group->$key = isset($group->$key)? $group->$key: [];
                array_push($group->$key, $modelContainer);
            }
            $group = (array)$group;
            if (count($group)) static::$lastResults = $group;
        }
        return static::$lastResults;
    }

    /**
     * Creates new model of the particular repository. The new model's class is to be specified by $modelClass value
     * New object will be created in Models namespace and will have the attributes specified by $params array
     *
     * @param array $params
     * @return Model
     */
    protected static function newModel(array $params = [])
    {
        $modelsNamespace = Model::getNamespace();
        $modelClass = $modelsNamespace . self::NAMESPACE_SEPARATOR . static::$modelClass;
        return new $modelClass($params);
    }

    /**
     * Returns JSON formatted list of model containers
     *
     * @param array $filter
     * @return string
     */
    public static function json(array $filter = [])
    {
        return json_encode(static::filter($filter));
    }
}
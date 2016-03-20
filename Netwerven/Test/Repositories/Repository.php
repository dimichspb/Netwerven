<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Exceptions\Exception;
use Netwerven\Test\Base\Patterns\Singleton;
use Netwerven\Test\DataSources\DataSource;
use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\Exceptions\InvalidRepositoryArgumentException;

/**
 * Class Repository
 * @package Netwerven\Test\Repositories
 */
abstract class Repository extends Component implements RepositoryInterface {

    use Singleton;

    /**
     * @var
     */
    protected static $modelClass;
    /**
     * @var
     */
    protected static $keyField;
    /**
     * @var array
     */
    protected static $lastResults = [];
    /**
     * @var array
     */
    protected static $dataSources = [];

    /**
     * @param $alias
     * @param DataSource $dataSource
     */
    public static function using($alias, DataSource $dataSource)
    {
        try {
            self::addToDataSourcesArray($alias, $dataSource);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). ' alias: '. $alias. '. value: '. serialize($dataSource));
        }
    }

    /**
     * @param $alias
     */
    public static function source($alias) {
        try {
            self::getFromDataSourcesArray($alias);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). 'alias: '. $alias);
        }
    }

    /**
     * @param $alias
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
     * @param $alias
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
     * @param $alias
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
     * @param $alias
     * @return bool
     */
    public static function isActive($alias)
    {
        try {
            return self::isDataSourceActive($alias);
        } catch (\Exception $e) {
            self::stdout($e->getMessage(). 'alias: '. $alias);
        }
    }

    /**
     * @param $alias
     * @param DataSource $dataSource
     */
    private static function addToDataSourcesArray($alias, DataSource $dataSource)
    {
        if (!self::isString($alias)) {
            return;
        }
        static::$dataSources[$alias] = [
            'active' => $dataSource->canBeActive(),
            'source' => $dataSource,
        ];
    }

    /**
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
     * @param $alias
     */
    private static function removeFromDataSourcesArray($alias)
    {
        if (self::isUsing($alias)) {
            unset(static::$dataSources[$alias]);
        }
    }

    /**
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
     * @param $alias
     */
    private static function setDataSourceInactive($alias)
    {
        if (static::isUsing($alias)) {
            static::$dataSources[$alias]['active'] = false;
        }
    }

    /**
     * @param $alias
     * @return bool
     */
    private static function isDataSourceActive($alias)
    {
        if (static::isUsing($alias)) {
            return static::$dataSources[$alias]['active'] === true;
        }
    }

    /**
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
     * @param $alias
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
     * @param array $filter
     * @return array
     */
    public static function all($filter = [])
    {
        return static::filter($filter);
    }

    /**
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
     * @param array $filter
     * @param string $alias
     * @return array
     */
    public static function filter(array $filter, $alias = '')
    {
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
     * @param int $index
     * @param array $filter
     * @param string $alias
     * @return array|mixed
     */
    public static function find($index = 0, array $filter = [], $alias = '')
    {
        $allModels = static::filter($filter, $alias);
        switch (gettype($index)) {
            case 'string':
                $allModels = (object)$allModels;
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
     * @param array $modelContainers
     */
    private static function mergeResults(array $modelContainers)
    {
        static::$lastResults = array_merge(static::$lastResults, $modelContainers);
    }

    /**
     * @param string $fieldName
     * @return array
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
     * @param array $params
     * @return mixed
     */
    protected static function newModel(array $params = [])
    {
        $modelsNamespace = Model::getNamespace();
        $modelClass = $modelsNamespace . self::NAMESPACE_SEPARATOR . static::$modelClass;
        return new $modelClass($params);
    }

    /**
     * @param array $filter
     * @return string
     */
    public static function json(array $filter = [])
    {
        return json_encode(static::sources());
    }
}
<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\Base\Component;
use Netwerven\Test\Base\Patterns\Singleton;
use Netwerven\Test\DataSources\DataSource;
use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\Exceptions\InvalidRepositoryArgumentException;

abstract class Repository extends Component implements RepositoryInterface {

    use Singleton;

    protected static $modelClass;

    protected static $dataSources = [];

    public static function using($alias, DataSource $dataSource)
    {
        try {
            self::addToDataSourcesArray($alias, $dataSource);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL, "alias: ", $alias, ", value: ", serialize($dataSource), PHP_EOL;
        }
    }

    public static function source($alias) {
        try {
            self::getFromDataSourcesArray($alias);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL, "alias: ", $alias, PHP_EOL;
        }
    }

    public static function unusing($alias)
    {
        try {
            self::removeFromDataSourcesArray($alias);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL, "alias: ", $alias, PHP_EOL;
        }
    }

    public static function activate($alias)
    {
        try {
            self::setDataSourceActive($alias);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL, "alias: ", $alias, PHP_EOL;
        }
    }

    public static function deactivate($alias)
    {
        try {
            self::setDataSourceInactive($alias);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL, "alias: ", $alias, PHP_EOL;
        }
    }

    public static function isActive($alias)
    {
        try {
            return self::isDataSourceActive($alias);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL, "alias: ", $alias, PHP_EOL;
        }
    }

    private static function addToDataSourcesArray($alias, DataSource $dataSource)
    {
        self::isString($alias);
        static::$dataSources[$alias] = [
            'active' => true,
            'source' => $dataSource,
        ];
    }

    private static function getFromDataSourcesArray($alias)
    {
        static::isUsing($alias);
        return static::$dataSources[$alias];
    }

    private static function removeFromDataSourcesArray($alias)
    {
        self::isUsing($alias);
        unset(static::$dataSources[$alias]);
    }

    private static function setDataSourceActive($alias)
    {
        if (static::isUsing($alias)) {
            static::$dataSources[$alias]['active'] = true;
        }
    }

    private static function setDataSourceInactive($alias)
    {
        if (static::isUsing($alias)) {
            static::$dataSources[$alias]['active'] = false;
        }
    }

    private static function isDataSourceActive($alias)
    {
        if (static::isUsing($alias)) {
            return static::$dataSources[$alias]['active'] === true;
        }
    }

    public static function isUsing($alias)
    {
        self::isString($alias);
        return isset(static::$dataSources[$alias]);
    }

    private static function isString($alias)
    {
        if (!is_string($alias)) {
            throw new InvalidRepositoryArgumentException('Alias name must be a string');
        }
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

    public static function all(array $filter = [])
    {
        $model = static::newModel($filter);
        $sources = static::sources();
        foreach($sources as $source) {
            $results = array_merge(isset($results)? $results: [], $source->all($model));
        }
        return isset($results)? $results: [];
    }

    public static function one($index, array $filter = [])
    {
        $allModels = static::all($filter);
        return isset($allModels[$index])? $allModels[$index]: null;
    }

    protected static function newModel(array $params = [])
    {
        $modelsNamespace = Model::getNamespace();
        $modelClass = $modelsNamespace . self::NAMESPACE_SEPARATOR . static::$modelClass;
        return new $modelClass($params);
    }

    public static function json(array $filter = [])
    {
        return json_encode(static::sources());
    }
}
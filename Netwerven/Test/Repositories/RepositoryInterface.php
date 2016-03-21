<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\DataSources\DataSource;
use Netwerven\Test\Models\Model;

/**
 * Interface RepositoryInterface
 * Describes the public methods of all repositories they must have
 *
 * @package Netwerven\Test\Repositories
 */
interface RepositoryInterface {

    /**
     * Sets the particular repository should use the provided DataSource with specified alias
     *
     * @param $alias
     * @param DataSource $dataSource
     * @return boolean  If DataSource has been add to the repository successfully
     */
    public static function using($alias, DataSource $dataSource);

    /**
     * Returns DataSource with specified alias which in use of the repository
     *
     * @param $alias
     * @return mixed|void
     */
    public static function source($alias);

    /**
     * Returns the array of all DataSources in use
     *
     * @return DataSource[]
     */
    public static function sources();

    /**
     * Sets the repository should not use the DataSource with specified $alias anymore
     *
     * @param $alias
     * @return mixed|void
     */
    public static function unusing($alias);

    /**
     * Sets the DataSource with specified alias active
     *
     * @param $alias
     * @return mixed|void
     */
    public static function activate($alias);

    /**
     * Sets the DataSource with the specified alias as inactive
     *
     * @param $alias
     * @return mixed|void
     */
    public static function deactivate($alias);

    /**
     * Checks whether the DataSource with specified alias is active or not
     *
     * @param $alias
     * @return bool
     */
    public static function isActive($alias);

    /**
     * Check whether the repository uses the DataSource with the specified alias
     *
     * @param $alias
     * @return bool
     */
    public static function isUsing($alias);

    /**
     * Returns first ModelContainer which contains the model with the specified value of key field ($index).
     * It is possible to specify the alias of the DataSource where to search for the model
     *
     * @param $index
     * @param $alias
     * @return mixed
     */
    public static function one($index, $alias);

    /**
     * Returns the array of models with the specified key field value ($index) from the particular repository filtered by $filter array.
     * It is possible to specify alias of the DataSource where to search for the models.
     *
     * @param int|string $index
     * @param array $filter
     * @param string $alias
     * @return array|mixed
     */
    public static function find($index = 0, array $filter = [], $alias = '');

    /**
     * Returns an array of ModelContainers which contain only the models similar to specified $filter array.
     * Groups the results by key field
     * It is possible to specify alias of the DataSource where to search for the models.
     *
     * @param array $filter
     * @param string $alias
     * @return ModelContainer[]
     */
    public static function filter(array $filter, $alias = '');

    /**
     * Returns the array of ModelContainers
     *
     * @return ModelContainer[]
     */
    public static function all();

    /**
     * Adds the specified $model to all DataSources of the particular repository
     *
     * @param Model $model
     * @param string $alias
     * @return bool
     */
    public static function add(Model $model, $alias = '');

    /**
     * Updates the specified $model at all DataSources of the particular repository
     *
     *
     * @param Model $model
     * @param string $alias
     * @return bool
     */
    public static function update(Model $model, $alias = '');

    /**
     * Deletes the specified $model at all DataSources of the particular repository
     *
     * @param Model $model
     * @param string $alias
     * @return bool
     */
    public static function delete(Model $model, $alias = '');

    /**
     * Returns JSON formatted list of model containers
     *
     * @param array $filter
     * @return string
     */
    public static function json(array $filter = []);

}
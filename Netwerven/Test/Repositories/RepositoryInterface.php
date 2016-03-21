<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\DataSources\DataSource;
use Netwerven\Test\Models\Model;

/**
 * Interface RepositoryInterface
 * @package Netwerven\Test\Repositories
 */
interface RepositoryInterface {

    /**
     * @param $alias
     * @param DataSource $dataSource
     * @return mixed
     */
    public static function using($alias, DataSource $dataSource);

    /**
     * @param $alias
     * @return mixed
     */
    public static function source($alias);

    /**
     * @return mixed
     */
    public static function sources();

    /**
     * @param $alias
     * @return mixed
     */
    public static function unusing($alias);

    /**
     * @param $alias
     * @return mixed
     */
    public static function activate($alias);

    /**
     * @param $alias
     * @return mixed
     */
    public static function deactivate($alias);

    /**
     * @param $alias
     * @return mixed
     */
    public static function isActive($alias);

    /**
     * @param $alias
     * @return mixed
     */
    public static function isUsing($alias);

    /**
     * @param $index
     * @param $alias
     * @return mixed
     */
    public static function one($index, $alias);

    /**
     * @param int $index
     * @param array $filter
     * @param string $alias
     * @return mixed
     */
    public static function find($index = 0, array $filter = [], $alias = '');

    /**
     * @param array $filter
     * @param string $alias
     * @return mixed
     */
    public static function filter(array $filter, $alias = '');

    /**
     * @return mixed
     */
    public static function all();

    /**
     * @param Model $model
     * @param string $alias
     * @return mixed
     */
    public static function add(Model $model, $alias = '');

    /**
     * @param Model $model
     * @param string $alias
     * @return mixed
     */
    public static function update(Model $model, $alias = '');

    /**
     * @param Model $model
     * @param string $alias
     * @return mixed
     */
    public static function delete(Model $model, $alias = '');

    /**
     * @param array $filter
     * @return mixed
     */
    public static function json(array $filter = []);

}
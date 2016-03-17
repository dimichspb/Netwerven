<?php
namespace Netwerven\Test\Repositories;

use Netwerven\Test\DataSources\DataSource;

interface RepositoryInterface {

    public static function using($alias, DataSource $dataSource);

    public static function source($alias);

    public static function unusing($alias);

    public static function activate($alias);

    public static function isActive($alias);

    public static function deactivate($alias);

    public static function json(array $filter = []);

}
<?php
namespace Netwerven\Test\Tests;

use Netwerven\Test\DataSources\JsonConfig;
use Netwerven\Test\DataSources\JsonDataSource;
use Netwerven\Test\Repositories\VacancyRepository as TestingRepository;

class VacancyRepositoryTest extends \PHPUnit_Framework_TestCase
{

    const TESTING_DATASOURCE_ALIAS = 'testingDataSource';
    const TESTING_DATASOURCE_CLASS = 'Netwerven\Test\DataSources\DataSource';
    const TESTING_JSON_FILENAME = 'temp.json';
    const TESTING_JSON_PRIMARYKEY = 'id';

    public $testingDataSource;
    public $testingDataSourceConfig;

    public function setUp()
    {
        require_once (dirname(__DIR__) . DIRECTORY_SEPARATOR . "Bootstrap.php");

        $this->testingDataSourceConfig = new JsonConfig([
            'filePath' => self::TESTING_JSON_FILENAME,
            'primaryKey' => self::TESTING_JSON_PRIMARYKEY,
            'mapping' => [],
        ]);
        $this->testingDataSource = new JsonDataSource($this->testingDataSourceConfig);
    }

    public function testUsing()
    {
        $result = TestingRepository::using(self::TESTING_DATASOURCE_ALIAS, $this->testingDataSource);
        $this->assertTrue($result);
    }

    /**
     * @depends testUsing
     */
    public function testSource()
    {
        $source = TestingRepository::source(self::TESTING_DATASOURCE_ALIAS);
        $this->assertInstanceOf(self::TESTING_DATASOURCE_CLASS, $source);
    }

    /**
     * @depends testUsing
     */
    public function testSources()
    {
        $sources = TestingRepository::sources();
        foreach ($sources as $source) {
            $this->assertInstanceOf(self::TESTING_DATASOURCE_CLASS, $source);
        }
    }

    /**
     * @depends testUsing
     * @depends testSource
     */
    public function testUnusing()
    {
        TestingRepository::unusing(self::TESTING_DATASOURCE_ALIAS);
        $unusingSource = TestingRepository::source(self::TESTING_DATASOURCE_ALIAS);
        $this->assertNull($unusingSource);
    }

    /**
     * @depends testUsing
     */
    public function testIsActive()
    {
        $result = TestingRepository::isActive(self::TESTING_DATASOURCE_ALIAS);
        $this->assertTrue(is_bool($result));
    }

    /**
     * @depends testUsing
     * @depends testIsActive
     */
    public function testActivate()
    {
        TestingRepository::activate(self::TESTING_DATASOURCE_ALIAS);
        var_dump(TestingRepository::source(self::TESTING_DATASOURCE_ALIAS));
        $result = TestingRepository::isActive(self::TESTING_DATASOURCE_ALIAS);
        var_dump($result);
        $this->assertTrue($result);
    }

    /**
     * @depends testUsing
     * @depends testIsActive
     */
    public function testDeactivate()
    {
        TestingRepository::deactivate(self::TESTING_DATASOURCE_ALIAS);
        $result = TestingRepository::isActive(self::TESTING_DATASOURCE_ALIAS);
        $this->assertFalse($result);
    }

    /**
     * @depends testUsing
     */
    public function testIsUsing()
    {
        $result = TestingRepository::isUsing(self::TESTING_DATASOURCE_ALIAS);
        $this->assertTrue(is_bool($result));
    }
/*
    public function testOne()
    {
    }

    public function testFind()
    {
    }

    public function testFilter()
    {
    }

    public function testAll()
    {
    }

    public function testAdd()
    {
    }

    public function testUpdate()
    {
    }

    public function testDelete()
    {
    }

    public function testJson()
    {
    }
*/
}

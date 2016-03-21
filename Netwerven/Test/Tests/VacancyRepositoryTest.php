<?php
namespace Netwerven\Test\Tests;

use Netwerven\Test\DataSources\JsonConfig;
use Netwerven\Test\DataSources\JsonDataSource;
use Netwerven\Test\Repositories\VacancyRepository as TestingRepository;
use Netwerven\Test\Models\Vacancy as TestingModel;

class VacancyRepositoryTest extends \PHPUnit_Framework_TestCase
{

    const TESTING_DATASOURCE_ALIAS = 'testingDataSource';
    const TESTING_DATASOURCE_CLASS = 'Netwerven\Test\DataSources\DataSource';
    const TESTING_MODEL_CLASS = 'Netwerven\Test\Models\Model';
    const TESTING_MODEL_CONTAINER_CLASS = 'Netwerven\Test\Repositories\ModelContainer';
    const TESTING_JSON_FILENAME = 'temp.json';
    const TESTING_JSON_PRIMARYKEY = 'id';

    private $testingDataSource;
    private $testingDataSourceConfig;
    private $usingMethodResult;
    private $exampleModel;
    private $exampleFilter;

    public function setUp()
    {
        require_once (dirname(__DIR__) . DIRECTORY_SEPARATOR . "Bootstrap.php");

        $this->testingDataSourceConfig = new JsonConfig([
            'filePath' => self::TESTING_JSON_FILENAME,
            'primaryKey' => self::TESTING_JSON_PRIMARYKEY,
            'mapping' => [],
        ]);
        $this->testingDataSource = new JsonDataSource($this->testingDataSourceConfig);
        $this->usingMethodResult = TestingRepository::using(self::TESTING_DATASOURCE_ALIAS, $this->testingDataSource);

        $exampleModelData = [
            'id' => '1',
            'title' => 'Developer1 title',
            'content' => 'Developer1 content',
            'description' => 'Developer1 description',
        ];
        $this->exampleModel = new TestingModel($exampleModelData);

        $exampleFilterData = [
            'title' => 'Developer1 title',
        ];
        $this->exampleFilter = $exampleFilterData;


    }

    public function testUsing()
    {
        $this->assertTrue($this->usingMethodResult);
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
        $result = TestingRepository::isActive(self::TESTING_DATASOURCE_ALIAS);
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

    /**
     * @depends testUsing
     */
    public function testOne()
    {
        TestingRepository::add($this->exampleModel);
        $model = TestingRepository::one($this->exampleModel->id, self::TESTING_DATASOURCE_ALIAS);
        $this->assertInstanceOf(self::TESTING_MODEL_CONTAINER_CLASS, $model);
    }

    /**
     * @depends testUsing
     * @depends testOne
     */
    public function testAdd()
    {
        $addResult = TestingRepository::add($this->exampleModel);
        $this->assertTrue(is_bool($addResult));
        $model = TestingRepository::one($this->exampleModel->id, self::TESTING_DATASOURCE_ALIAS);
        $this->assertInstanceOf(self::TESTING_MODEL_CONTAINER_CLASS, $model);

    }

    /**
     * @depends testUsing
     * @depends testAdd
     * @depends testOne
     */
    public function testUpdate()
    {
        TestingRepository::add($this->exampleModel);
        $result = TestingRepository::update($this->exampleModel);
        $this->assertTrue($result);
        $model = TestingRepository::one($this->exampleModel->id, self::TESTING_DATASOURCE_ALIAS);
        $this->assertInstanceOf(self::TESTING_MODEL_CONTAINER_CLASS, $model);
    }

    /**
     * @depends testUsing
     * @depends testAdd
     * @depends testOne
     */
    public function testDelete()
    {
        TestingRepository::add($this->exampleModel);
        $result = TestingRepository::delete($this->exampleModel);
        $this->assertTrue($result);
        $model = TestingRepository::one($this->exampleModel->id, self::TESTING_DATASOURCE_ALIAS);
        $this->assertTrue(count($model) === 0);
    }

    /**
     * @depends testUsing
     * @depends testAdd
     */
    public function testFind()
    {
        TestingRepository::add($this->exampleModel);
        $result = TestingRepository::find($this->exampleModel->id);
        $this->assertArrayHasKey(0, $result);
        $this->assertInstanceOf(self::TESTING_MODEL_CONTAINER_CLASS, $result[0]);
    }

    /**
     * @depends testUsing
     * @depends testAdd
     */
    public function testFilter()
    {
        TestingRepository::add($this->exampleModel);
        $results = TestingRepository::filter($this->exampleFilter);
        foreach ($results as $result) {
            foreach ($result as $item) {
                $this->assertInstanceOf(self::TESTING_MODEL_CONTAINER_CLASS, $item);
            }
        }
    }

    /**
     * @depends testUsing
     * @depends testAdd
     */
    public function testAll()
    {
        TestingRepository::add($this->exampleModel);
        $results = TestingRepository::all();
        foreach ($results as $result) {
            foreach ($result as $item) {
                $this->assertInstanceOf(self::TESTING_MODEL_CONTAINER_CLASS, $item);
            }
        }
    }

    /**
     * @depends testUsing
     */
    public function testJson()
    {
        TestingRepository::add($this->exampleModel);
        $json = TestingRepository::json();
        $this->assertJson($json);
    }

}

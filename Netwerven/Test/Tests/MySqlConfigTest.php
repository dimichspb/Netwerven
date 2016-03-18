<?php
namespace Netwerven\Test\Tests;

use Netwerven\Test\DataSources\Exceptions\InvalidConfigArgumentException;
use Netwerven\Test\DataSources\MySqlConfig;

class MySqlConfigTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        require_once (dirname(__DIR__) . DIRECTORY_SEPARATOR . "Bootstrap.php");
    }
    /**
     * @dataProvider incorrectArrayProvider
     * @expectedException InvalidConfigArgumentException
     */
    public function testException(array $array)
    {
        //new MySqlConfig($array);
    }

    public function correctArrayProvider()
    {
        return [
            [[
                'dbhost' => 'localhost',
                'dbname' => 'endouble1',
                'dbuser' => 'endouble',
                'dbpass' => 'endouble',
                'mapping' => [
                    'Vacancy' => 'vacancy',
                ]
            ]],
            [[
                'dbhost' => 'localhost',
                'dbname' => 'endouble2',
                'dbuser' => 'endouble',
                'dbpass' => 'endouble',
                'mapping' => [
                    'Vacancy' => 'vacancy',
                ]
            ]],
        ];
    }

    public function incorrectArrayProvider()
    {
        return [
            [[
                'dbhost' => 'localhost',
                'dbname' => 'endouble1',
//                'dbuser' => 'endouble',
//                'dbpass' => 'endouble',
                'mapping' => [
                    'Vacancy' => 'vacancy',
                ]
            ]],
            [[
                'dbhost' => 'localhost',
                'dbname' => 'endouble1',
                'dbuser' => 'endouble',
                'dbpass' => 'endouble',
                'mapping' => [
//                    'Vacancy' => 'vacancy',
                ]
            ]],
            [[
                'dbhost' => 'localhost',
                'dbname' => 'endouble1',
                'dbuser' => 'endouble',
                'dbpass' => 'endouble',
//                'mapping' => [
//                    'Vacancy' => 'vacancy',
//                ]
            ]],
            [[
                'missingAttribute' => 'localhost',
                'dbname' => 'endouble1',
                'dbuser' => 'endouble',
                'dbpass' => 'endouble',
                'mapping' => [
                    'Vacancy' => 'vacancy',
                ]
            ]],
            [[]],
        ];
    }
}

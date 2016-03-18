<?php
require_once (__DIR__ . DIRECTORY_SEPARATOR . "Test" . DIRECTORY_SEPARATOR . "Bootstrap.php");

use Netwerven\Test\DataSources\MySqlDataSource;
use Netwerven\Test\DataSources\MySqlConfig;
use Netwerven\Test\Repositories\VacancyRepository;
use Netwerven\Test\Models\Vacancy;

$connection1 = new MySqlConfig([
    'dbhost' => 'localhost',
    'dbname' => 'endouble1',
 //   'dbuser' => 'endouble',
    'dbpass' => 'endouble',
    'mapping' => [
        'Vacancy' => 'vacancy',
    ]
]);

$connection2 = new MySqlConfig([
    'dbhost' => 'localhost',
    'dbname' => 'endouble2',
    'dbuser' => 'endouble',
    'dbpass' => 'endouble',
    'mapping' => [
        'Vacancy' => 'vacancy'
    ]
]);

$mySqlFirst = new MySqlDataSource($connection1);
$mySqlSecond = new MySqlDataSource($connection2);

VacancyRepository::using('first_mySql', $mySqlFirst);
VacancyRepository::using('second_mySql', $mySqlSecond);
//VacancyRepository::deactivate('second_mySql');

$filter = [
    'id' => 2,
];

//var_dump(VacancyRepository::find("1"));

$data = [
    'id' => 5,
    'title' => 'developer3 common',
];

$vacancy = new Vacancy($data);

var_dump(VacancyRepository::add($vacancy));

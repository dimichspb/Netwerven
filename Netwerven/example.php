<?php
// Bootstrapping example
require_once (__DIR__ . DIRECTORY_SEPARATOR . "Test" . DIRECTORY_SEPARATOR . "Bootstrap.php");

use Netwerven\Test\DataSources\MySqlDataSource;
use Netwerven\Test\DataSources\MySqlConfig;
use Netwerven\Test\DataSources\JsonDataSource;
use Netwerven\Test\DataSources\JsonConfig;
use Netwerven\Test\Repositories\VacancyRepository;
use Netwerven\Test\Models\Vacancy;


// Creating example mysql database config
$connection1 = new MySqlConfig([
    'dbhost' => 'localhost',
    'dbname' => 'endouble1',
    'dbuser' => 'endouble',
    'dbpass' => 'endouble',
    'mapping' => [
        'Vacancy' => 'vacancy',
    ]
]);

// Creating example mysql database config
$connection2 = new MySqlConfig([
    'dbhost' => 'localhost',
    'dbname' => 'endouble2',
    'dbuser' => 'endouble',
    'dbpass' => 'endouble',
    'mapping' => [
        'Vacancy' => 'vacancy'
    ]
]);

// Creating example json file config
$json = new JsonConfig([
    'filePath' => 'temp.json',
    'primaryKey' => 'id',
    'mapping' => [],
]);

// Creating data sources
$mySqlFirst = new MySqlDataSource($connection1);
$mySqlSecond = new MySqlDataSource($connection2);
$jsonFirst = new JsonDataSource($json);

// Adding data sources to VacancyRepositories
VacancyRepository::using('first_mySql', $mySqlFirst);
VacancyRepository::using('second_mySql', $mySqlSecond);
VacancyRepository::using('first_json', $jsonFirst);
//VacancyRepository::deactivate('second_mySql');

var_dump(VacancyRepository::sources());

// Example data
$data1 = [
    'id' => 1,
    'title' => 'developer1 title',
    'content' => 'developer1 content',
    'description' => 'developer1 description',
];
$data2 = [
    'id' => 2,
    'title' => 'developer2 title',
    'content' => 'developer2 content',
    'description' => 'developer2 description',
];
$data3 = [
    'id' => 3,
    'title' => 'developer3 title',
    'content' => 'developer3 content',
    'description' => 'developer3 description',
];

// Creating example Vacancy models
$vacancy1 = new Vacancy($data1);
$vacancy2 = new Vacancy($data2);
$vacancy3 = new Vacancy($data3);


// Adding example Vacancy models to VacancyRepository
var_dump(VacancyRepository::add($vacancy1));
var_dump(VacancyRepository::add($vacancy2));
var_dump(VacancyRepository::add($vacancy3));

// Showing all data from VacancyRepository
var_dump(VacancyRepository::all());


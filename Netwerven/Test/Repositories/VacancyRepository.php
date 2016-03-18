<?php
namespace Netwerven\Test\Repositories;

/**
 * Class VacancyRepository
 * @package Netwerven\Test\Repositories
 */
class VacancyRepository extends Repository {

    /**
     * @var string
     */
    public static $modelClass = "Vacancy";

    /**
     * @var string
     */
    public static $keyField = "id";

}
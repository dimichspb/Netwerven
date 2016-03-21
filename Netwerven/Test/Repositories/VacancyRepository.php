<?php
namespace Netwerven\Test\Repositories;

/**
 * Class VacancyRepository
 * Contains methods to work with repository of Vacancies.
 * Vacancies should have a key field
 *
 * @package Netwerven\Test\Repositories
 */
class VacancyRepository extends Repository {

    /**
     * Class of the Models which VacancyRepository contains
     *
     * @var string
     */
    public static $modelClass = "Vacancy";

    /**
     * Key field name
     *
     * @var string
     */
    public static $keyField = "id";

}
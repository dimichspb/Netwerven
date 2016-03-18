<?php
namespace Netwerven\Test\Models;

/**
 * A test vacancy model
 * @package Netwerven\Test\Models
 */
class Vacancy extends Model {

    /**
     * The id of the vacancy
     *
     * @var integer
     */
    public $id;

    /**
     * The vacancy title
     *
     * @var string
     */
    public $title;

    /**
     * The vacancy content/description
     *
     * @var string
     */
    public $content;

    /**
     * The vacancy description
     *
     * @var string
     */
    public $description;
}

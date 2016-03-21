<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\DataSources\Exceptions\QueryException;
use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\ModelContainer;


/**
 * Class MySqlDataSource
 * Example MySQL database DataSource class
 *
 * @package Netwerven\Test\DataSources
 */
class MySqlDataSource extends DbDataSource {

    /**
     * MySQLi object
     *
     * @var \mysqli
     */
    private $connection;

    /**
     * MySqlDataSource constructor.
     * Tries to connect to MySQL database using MySQLConfig. Notice if connection error or sets $connection attribute if success
     *
     * @param MySqlConfig $config
     */
    public function __construct(MySqlConfig $config)
    {
        $this->config = $config;
        if (!$config->checkAttributes()) {
            return;
        }
        try {
            $mysqli = new \mysqli($config->get('dbhost'), $config->get('dbuser'), $config->get('dbpass'), $config->get('dbname'));
            if ($mysqli->connect_errno) {
                $this->stdout('Error connecting to MySQL database: (' . $mysqli->connect_errno . '), ' . $mysqli->connect_error);
            } else {
                $this->connection = $mysqli;
            }
        } catch (\Exception $e) {
            $this->stdout('Error connecting to MySQL database: ' . $e->getMessage());
        }
    }

    /**
     * Returns all models from the particular DataSource which are similar to the provided one
     *
     * @param Model $model
     * @return ModelContainer[]
     */
    public function filter(Model $model)
    {
        $shortClassName = $model->getShortClassName();
        $fullClassName = $model->getClassName();
        $tableName = $this->getMapping($shortClassName);
        $filter = $model->asArray();
        $rows = $this->_select($tableName, $filter);
        foreach ($rows as $row) {
            $model = new $fullClassName($row);
            $results[] = new ModelContainer($model, $this);
        }

        return isset($results)? $results: [];
    }

    /**
     * Adds model to the particular DataSource
     *
     * @param Model $model
     * @return bool|\mysqli_result
     */
    public function add(Model $model)
    {
        $shortClassName = $model->getShortClassName();
        $tableName = $this->getMapping($shortClassName);
        $newRow = $model->asArray();

        return $this->_insert($tableName, $newRow);
    }

    /**
     * Updates the model at the particular DataSource
     *
     * @param Model $model
     * @return bool|\mysqli_result
     */
    public function update(Model $model)
    {
        $shortClassName = $model->getShortClassName();
        $tableName = $this->getMapping($shortClassName);
        $row = $model->asArray();

        return $this->_update($tableName, $row);
    }

    /**
     * Deletes the model from the particular DataSource
     *
     * @param Model $model
     * @return bool|\mysqli_result
     */
    public function delete(Model $model)
    {
        $shortClassName = $model->getShortClassName();
        $tableName = $this->getMapping($shortClassName);
        $row = $model->asArray();

        return $this->_delete($tableName, $row);
    }

    /**
     * Check whether the DataSource can be active or not
     *
     * @return bool
     */
    public function canBeActive()
    {
        return isset($this->connection);
    }

    /**
     * Returns name of primary key field
     *
     * @param $tableName
     * @return mixed
     */
    protected function getPrimaryKey($tableName)
    {
        $queryString = "SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'";
        return($this->fetchArray($queryString)[0]['Column_name']);
    }

    /**
     * Returns array of resulting rows
     *
     * @param $queryString
     * @return mixed
     */
    protected function fetchArray($queryString)
    {
        if ($result = $this->query($queryString)) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    /**
     * Queries the data with the specified query string
     *
     * @param $queryString
     * @return bool|\mysqli_result
     */
    protected function query($queryString)
    {
        if ($this->connection) {
            //$escapedQueryString = $this->connection->real_escape_string($queryString);
            $escapedQueryString = $queryString;
            try {
                $queryResult = $this->connection->query($escapedQueryString);
                if ($this->connection->errno) {
                    throw new QueryException('Error querying from MySQL database: (' . $this->connection->errno . '), ' . $this->connection->error);
                }
                return $queryResult;
            } catch (\Exception $e) {
                $this->stdout('Query error: '. $e->getMessage());
            }
        }
    }
}
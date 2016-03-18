<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\DataSources\Exceptions\QueryException;
use Netwerven\Test\DataSources\Exceptions\DataException;
use Netwerven\Test\DataSources\Exceptions\InvalidConfigException;
use Netwerven\Test\DataSources\Exceptions\ConnectException;
use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\ModelContainer;


/**
 * Class MySqlDataSource
 * @package Netwerven\Test\DataSources
 */
class MySqlDataSource extends DataSource {

    /**
     * @var \mysqli
     */
    private $connection;

    /**
     *
     */
    const DELETE_STATEMENT = 'DELETE';
    /**
     *
     */
    const UPDATE_STATEMENT = 'UPDATE';
    /**
     *
     */
    const INSERT_STATEMENT = 'INSERT';
    /**
     *
     */
    const SELECT_STATEMENT = 'SELECT';

    /**
     * MySqlDataSource constructor.
     * @param DataSourceConfig|null $config
     */
    public function __construct(DataSourceConfig $config)
    {
        $this->config = $config;
        if (!$config->checkAttributes()) {
            return;
        }
        try {
            $mysqli = new \mysqli($config->get('dbhost'), $config->get('dbuser'), $config->get('dbpass'), $config->get('dbname'));
            if ($mysqli->connect_errno) {
                echo ('Error connecting to MySQL database: (' . $mysqli->connect_errno . '), ' . $mysqli->connect_error);
            } else {
                $this->connection = $mysqli;
            }
        } catch (\Exception $e) {
            echo ('Error connecting to MySQL database: ' . $e->getMessage());
        }
    }

    /**
     * @param Model $model
     * @return array
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
     * @param $tableName
     * @param array $where
     * @return mixed
     */
    private function _select($tableName, array $where = [])
    {
        try {
            $queryString = $this->prepareQuery(self::SELECT_STATEMENT, $tableName, $where);
            return $this->fetchArray($queryString);
        } catch (\Exception $e) {
            echo "Error selecting data: ", $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * @param $tableName
     * @param array $data
     * @return bool|\mysqli_result
     */
    private function _insert($tableName, array $data)
    {
        try {
            $queryString = $this->prepareQuery(self::INSERT_STATEMENT, $tableName, [], $data);
            return $this->query($queryString);
        } catch (\Exception $e) {
            echo "Error inserting data: ", $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * @param $tableName
     * @param array $data
     * @return bool|\mysqli_result
     */
    private function _update($tableName, array $data)
    {
        try {
            $queryString = $this->prepareQuery(self::UPDATE_STATEMENT, $tableName, [], $data);
            return $this->query($queryString);
        } catch (\Exception $e) {
            echo "Error updating data: ", $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * @param $tableName
     * @param array $data
     * @return bool|\mysqli_result
     */
    private function _delete($tableName, array $data)
    {
        try {
            $queryString = $this->prepareQuery(self::DELETE_STATEMENT, $tableName, [], $data);
            return $this->query($queryString);
        } catch (\Exception $e) {
            echo "Error deleting data: ", $e->getMessage(), PHP_EOL;
        }
    }

    /**
     * @param $queryType
     * @param $tableName
     * @param array $conditions
     * @param array $data
     * @return string
     */
    private function prepareQuery($queryType, $tableName, array $conditions = [], array $data = [])
    {
        switch ($queryType) {
            case self::SELECT_STATEMENT:
                $whereArray = [];
                foreach ($conditions as $index => $value) {
                    if (!empty($value)) {
                        $whereArray[] = "`$index` = '$value'";
                    }
                }
                $whereString = count($whereArray) ? implode(" AND ", $whereArray) : "1";
                return "SELECT * FROM `$tableName` WHERE $whereString";

            case self::INSERT_STATEMENT:
                if (!count($data)) {
                    throw new DataException('Data array must be provided for INSERT statement');
                }
                $columnsArray = array_keys($data);
                $valuesArray = array_values($data);

                $columnsString = '(`' . implode('`, `', $columnsArray) . '`)';
                $valuesString = "('" . implode("', '", $valuesArray) . "')";
                return "INSERT INTO `$tableName` $columnsString VALUES $valuesString";

            case self::UPDATE_STATEMENT:
                if (!count($data)) {
                    throw new DataException('Data array must be provided for UPDATE statement');
                }
                $pk = $this->getPrimaryKey($tableName);
                if (!isset($data[$pk])) {
                    throw new DataException('Data array must contain PrimaryKey field ' . $pk);
                }
                $valuesArray = [];
                $keyValue = $data[$pk];
                $whereString = "`$pk` = '$keyValue'";
                unset($data[$pk]);
                foreach ($data as $index => $value) {
                    if (!empty($value)) {
                        $valuesArray[] = "`$index` = '$value'";
                    }
                }

                $valuesString = count($valuesArray) ? implode(", ", $valuesArray) : "1";
                return "UPDATE `$tableName` SET $valuesString WHERE $whereString";

            case self::DELETE_STATEMENT:
                $pk = $this->getPrimaryKey($tableName);
                if (!isset($data[$pk])) {
                    throw new DataException('Data array must contain PrimaryKey field ' . $pk);
                }
                $keyValue = $data[$pk];
                $whereString = "`$pk` = '$keyValue'";

                return "DELETE FROM `$tableName` WHERE $whereString";

            default:
                return "";
        }
    }

    /**
     * @param $tableName
     * @return mixed
     */
    private function getPrimaryKey($tableName)
    {
        $queryString = "SHOW KEYS FROM $tableName WHERE Key_name = 'PRIMARY'";
        return($this->fetchArray($queryString)[0]['Column_name']);
    }

    /**
     * @param $queryString
     * @return mixed
     */
    private function fetchArray($queryString)
    {
        $result = $this->query($queryString);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @param $queryString
     * @return bool|\mysqli_result
     */
    private function query($queryString)
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
                echo "Query error: ", $e->getMessage(), PHP_EOL;
            }
        }
    }
}
<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\DataSources\Exceptions\DataException;

/**
 * Class DbDataSource
 * Contains base methods of the DataSources which works with different databases
 *
 * @package Netwerven\Test\DataSources
 */
abstract class DbDataSource extends DataSource {

    /**
     * Default DELETE statement
     *
     *
     */
    const DELETE_STATEMENT = 'DELETE';

    /**
     * Default UPDATE statement
     *
     *
     */
    const UPDATE_STATEMENT = 'UPDATE';

    /**
     * Default INSERT statement
     *
     */
    const INSERT_STATEMENT = 'INSERT';

    /**
     * Default SELECT statement
     */
    const SELECT_STATEMENT = 'SELECT';


    /**
     * Selects the data from database
     *
     * @param $tableName
     * @param array $where
     * @return array
     */
    protected function _select($tableName, array $where = [])
    {
        try {
            $queryString = $this->prepareQuery(self::SELECT_STATEMENT, $tableName, $where);
            return $this->fetchArray($queryString);
        } catch (\Exception $e) {
            $this->stdout('Error selecting data: '. $e->getMessage());
            return [];
        }
    }

    /**
     * Inserts data to database
     *
     * @param $tableName
     * @param array $data
     * @return bool|\mysqli_result
     */
    protected function _insert($tableName, array $data)
    {
        try {
            $queryString = $this->prepareQuery(self::INSERT_STATEMENT, $tableName, [], $data);
            return $this->query($queryString);
        } catch (\Exception $e) {
            $this->stdout('Error inserting data: '. $e->getMessage());
        }
    }

    /**
     * Updates data in database
     *
     * @param $tableName
     * @param array $data
     * @return bool|\mysqli_result
     */
    protected function _update($tableName, array $data)
    {
        try {
            $queryString = $this->prepareQuery(self::UPDATE_STATEMENT, $tableName, [], $data);
            return $this->query($queryString);
        } catch (\Exception $e) {
            $this->stdout('Error updating data: '. $e->getMessage());
        }
    }

    /**
     * Deletes data from database
     *
     * @param $tableName
     * @param array $data
     * @return bool|\mysqli_result
     */
    protected function _delete($tableName, array $data)
    {
        try {
            $queryString = $this->prepareQuery(self::DELETE_STATEMENT, $tableName, [], $data);
            return $this->query($queryString);
        } catch (\Exception $e) {
            $this->stdout('Error deleting data: '. $e->getMessage());
        }
    }

    /**
     * Prepares queries
     *
     * TODO:: remove $conditions param, use $data instead
     *
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
     * Returns primary key of the table
     *
     * @param $tableName
     * @return string
     */
    abstract protected function getPrimaryKey($tableName);

    /**
     * Returns array of resulting rows
     *
     * @param $queryString
     * @return array
     */
    abstract protected function fetchArray($queryString);

    /**
     * Queries the data with the specified query string
     *
     * @param $queryString
     * @return mixed
     */
    abstract protected function query($queryString);
}
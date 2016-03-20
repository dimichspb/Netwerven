<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\DataSources\Exceptions\DataException;

abstract class DbDataSource extends DataSource {

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
     * @param $tableName
     * @param array $where
     * @return mixed
     */
    protected function _select($tableName, array $where = [])
    {
        try {
            $queryString = $this->prepareQuery(self::SELECT_STATEMENT, $tableName, $where);
            return $this->fetchArray($queryString);
        } catch (\Exception $e) {
            $this->stdout('Error selecting data: '. $e->getMessage());
        }
    }

    /**
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

    abstract protected function getPrimaryKey($tableName);

    abstract protected function fetchArray($queryString);

    abstract protected function query($queryString);
}
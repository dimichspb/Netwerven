<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\DataSources\Exceptions\InvalidConfigException;
use Netwerven\Test\DataSources\Exceptions\ConnectException;
use Netwerven\Test\Models\Model;


class MySqlDataSource extends DataSource {

    private $connection;

    public function __construct(DataSourceConfig $config = null)
    {
        if (is_null($config)) {
            throw new InvalidConfigException();
        }
        $this->config = $config;
        try {
            $mysqli = new \mysqli($config->get('dbhost'), $config->get('dbuser'), $config->get('dbpass'), $config->get('dbname'));
            if ($mysqli->connect_errno) {
                throw new ConnectException('Error connecting to MySQL database: (' . $mysqli->connect_errno . '), ' . $mysqli->connect_error);
            }
            $this->connection = $mysqli;
        } catch (\Exception $e) {
            echo ('Error connecting to MySQL database: ' . $e->getMessage());
        }
    }

    public function all(Model $model)
    {
        $shortClassName = $model->getShortClassName();
        $fullClassName = $model->getClassName();
        $tableName = $this->getMapping($shortClassName);
        $filter = $model->asArray();
        $rows = $this->select($tableName, $filter);
        foreach ($rows as $row) {
            $row['dataSource'] = $this;
            $result[] = new $fullClassName($row);
        }

        return isset($result)? $result: [];
    }

    private function select($tableName, array $where = [])
    {
        $queryString = $this->prepareQuery('SELECT', $tableName, $where);
        return $this->fetchArray($queryString);
    }

    private function prepareQuery($queryType, $tableName, array $conditions = [], array $values = [])
    {
        switch ($queryType) {
            case 'SELECT':
                $whereArray = [];
                foreach ($conditions as $index => $value) {
                    if (!empty($value)) {
                        $whereArray[] = "`$index` = $value";
                    }
                }
                $whereString = count($whereArray) ? implode(" AND ", $whereArray) : "1";
                return "SELECT * FROM `$tableName` WHERE $whereString";
            default:
                return "";
        }
    }

    private function fetchArray($queryString)
    {
        $result = $this->query($queryString);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function query($queryString)
    {
        if ($this->connection) {
            $escapedQueryString = $this->connection->real_escape_string($queryString);
            return $this->connection->query($escapedQueryString);
        }
    }
}
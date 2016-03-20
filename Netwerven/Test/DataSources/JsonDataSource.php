<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Models\Model;

class JsonDataSource extends DataSource {

    public function __construct($filePath, $primaryKey)
    {
        if (!file_exists($filePath) && !fopen($filePath, "w")) {
            $this->stdout('Can\'t create file ' . $filePath);
        }
        if (!is_writable($filePath)) {
            $this->stdout('Can\'t write to file ' . $filePath);
        }
        $this->config = [
            'filePath' => $filePath,
            'primaryKey' => $primaryKey,
        ];
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function filter(Model $model)
    {
        $key = array_search(40489, array_column($userdb, 'uid'));
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function add(Model $model)
    {
        $filePath = $this->config['filePath'];
        $jsonFile = file_get_contents($filePath);
        if ($tempArray = json_decode($jsonFile, true)) {
            if (isset($))
            array_push($tempArray, (array)$model);
        } else {
            $tempArray = (array)$model;
        }
        $jsonData = json_encode($tempArray);
        file_put_contents($filePath, $jsonData);
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function update(Model $model)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function delete(Model $model)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return mixed
     */
    public function canBeActive()
    {
        return is_writable($this->config['filePath']);
    }

    public function getPrimaryKey()
    {
        return $this->config['primaryKey'];
    }
}
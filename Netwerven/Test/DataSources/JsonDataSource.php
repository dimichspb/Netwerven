<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\ModelContainer;

/**
 * Class JsonDataSource
 * Example class to access json data
 *
 * @package Netwerven\Test\DataSources
 */
class JsonDataSource extends DataSource {

    /**
     * JsonDataSource constructor.
     * Creates file or check whether it is writable or not
     *
     * @param JsonConfig $jsonConfig
     */
    public function __construct(JsonConfig $jsonConfig)
    {
        $this->config = $jsonConfig;
        $filePath = $jsonConfig->get('filePath');
        if (!file_exists($filePath) && !fopen($filePath, "w")) {
            $this->stdout('Can\'t create file ' . $filePath);
        }
        if (!is_writable($filePath)) {
            $this->stdout('Can\'t write to file ' . $filePath);
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
        $fullClassName = $model->getClassName();
        $dataArray = $this->getArray();
        $filterArray = $model->asArray();
        $rows = $this->md_search($filterArray, $dataArray);
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
     * @return boolean
     */
    public function add(Model $model)
    {
        $dataArray = $this->getArray();
        $sameRow = $this->pkFilter($model);

        if ($sameRow !== false) {
            $this->stdout('Row with primary key exists');
            return false;
        }
        array_push($dataArray, $model->asArray());
        return $this->putArray($dataArray);
    }

    /**
     * Updates the model at the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    public function update(Model $model)
    {
        $dataArray = $this->getArray();
        $rowToUpdate = $this->pkFilter($model);

        if ($rowToUpdate === false) {
            $this->stdout('Can\'t find row with this primary key');
            return false;
        }
        $dataArray[$rowToUpdate] = $model->asArray();
        return $this->putArray($dataArray);
    }

    /**
     * Deletes the model from the particular DataSource
     *
     * @param Model $model
     * @return boolean
     */
    public function delete(Model $model)
    {
        $dataArray = $this->getArray();
        $rowToDelete = $this->pkFilter($model);

        if ($rowToDelete === false) {
            $this->stdout('Can\'t find row with this primary key');
            return false;
        }
        unset ($dataArray[$rowToDelete]);
        $dataArray = array_merge($dataArray);
        return $this->putArray($dataArray);
    }

    /**
     * Check whether the DataSource can be active or not
     *
     * @return boolean
     */
    public function canBeActive()
    {
        return $this->isWritable();
    }

    /**
     * Returns name of primary key field
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->config->get('primaryKey');
    }

    /**
     * Return the data from json file as array
     *
     * @return array
     */
    private function getArray()
    {
        if ($this->isWritable()) {
            $jsonDecode = json_decode($this->getFileContent(), true);
            return $jsonDecode? $jsonDecode: [];
        }
    }

    /**
     * Put $data array to the json file
     *
     * @param array $data
     * @return int
     */
    private function putArray(array $data)
    {
        if ($this->isWritable()) {
            return $this->putFileContent(json_encode($data));
        }
    }

    /**
     * Return the content of json file as plain text
     *
     * @return string
     */
    private function getFileContent()
    {
        if ($this->isWritable()) {
            return file_get_contents($this->config->get('filePath'));
        }
    }

    /**
     * Puts the plain text to the file
     *
     * @param $data
     * @return int
     */
    private function putFileContent($data)
    {
        if ($this->isWritable()) {
            return file_put_contents($this->config->get('filePath'), $data);
        }
    }

    /**
     * Check whether the file is writable or not
     *
     * @return bool
     */
    private function isWritable()
    {
        return is_writable($this->config->get('filePath'));
    }

    /**
     * Multidimensional search in array
     *
     * @param array $needle
     * @param array $haystack
     * @return array
     */
    private function md_search(array $needle, array $haystack)
    {
        $result = [];

        if (count($needle) === 0  || count($haystack) === 0) {
            return $result;
        }

        foreach ($haystack as $hkey => $hvalue) {
            $found = 0;
            foreach ($needle as $nkey => $nvalue) {
                if (is_null($nvalue) || (isset($haystack[$hkey][$nkey]) && $haystack[$hkey][$nkey] == $nvalue)) {
                    $found++;
                }
            }
            if ($found == sizeof($needle)) $result[$hkey] = $hvalue;
        }
        return $result;
    }

    /**
     * Returns the index of the model from json file with the same primary key
     *
     * @param Model $model
     * @return int|bool
     */
    private function pkFilter(Model $model)
    {
        $modelClass = $model->getClassName();
        $primaryKey = $this->getPrimaryKey();
        $filterModel = new $modelClass;
        $filterModel->$primaryKey = $model->$primaryKey;
        if (!$resultArray = $this->filter($filterModel)) {
            return false;
        }
        $resultKeys = array_keys($resultArray);
        return count($resultKeys)? $resultKeys[0]: false;
    }
}
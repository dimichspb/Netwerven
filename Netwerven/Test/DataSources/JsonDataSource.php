<?php
namespace Netwerven\Test\DataSources;

use Netwerven\Test\Models\Model;
use Netwerven\Test\Repositories\ModelContainer;

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
     * @param Model $model
     * @return mixed
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
     * @param Model $model
     * @return mixed
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
     * @param Model $model
     * @return mixed
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
     * @return mixed
     */
    public function canBeActive()
    {
        return $this->isWritable();
    }

    public function getPrimaryKey()
    {
        return $this->config['primaryKey'];
    }

    private function getArray()
    {
        if ($this->isWritable()) {
            $jsonDecode = json_decode($this->getFileContent(), true);
            return $jsonDecode? $jsonDecode: [];
        }
    }

    private function putArray(array $data)
    {
        if ($this->isWritable()) {
            return $this->putFileContent(json_encode($data));
        }
    }

    private function getFileContent()
    {
        if ($this->isWritable()) {
            return file_get_contents($this->config['filePath']);
        }
    }

    private function putFileContent($data)
    {
        if ($this->isWritable()) {
            return file_put_contents($this->config['filePath'], $data);
        }
    }

    private function isWritable()
    {
        return is_writable($this->config['filePath']);
    }

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
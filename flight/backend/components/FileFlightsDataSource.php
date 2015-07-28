<?php

namespace backend\components;

/**
 * Description of FileFlightsDataSource
 *
 * @author chriss
 */
class FileFlightsDataSource implements IFlightsDataSource {
    
    public $sourceFile = 'task_xml.txt'; /* впоследствии можно вынести в настройки модуля*/
    
    public function getData() {
        return file_get_contents(\Yii::$app->basePath . DIRECTORY_SEPARATOR . $this->sourceFile);
    }
}
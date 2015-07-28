<?php

namespace backend\components;

/**
 * Description of FileFlightsDataSource
 *
 * @author chriss
 */
class FileFlightsDataSource implements IFlightsDataSource {
    
    public $sourceFile = '@webroot/task_xml.txt';
    
    public function getData() {
        return file_get_contents($this->sourceFile);
    }
}
<?php

namespace backend\components;

/**
 * @author chriss
 */
class SearchFlight
{
    /**
     * 
     * @return IFlightsDataSource
     */
    public function getDataSource() {
        return new FileFlightsDataSource(); /** имя класса можно будет вынести в настройки */
    }
    
    /**
     * 
     * @return IFlightsDataParser
     */
    public function getDataParser() {
        return new XMLFlightsDataParser();
    }
    
    public function sendAndSave() {
        $this->getDataParser()->getFlightsData($this->getDataSource()->getData());
    }
}

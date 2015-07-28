<?php

namespace backend\components;

/**
 * Description of XMLFlightsDataParser
 *
 * @author chriss
 */
class XMLFlightsDataParser implements IFlightsDataParser {
    
    public function getFlightsData($xmlSource) {
        $parser = new \light\yii2\XmlParser(); /* впоследствии можно вызывать через DI, указывая в настройках имя класса-парсера */
        var_dump($parser->parse($xmlSource, null));
    }
            
}
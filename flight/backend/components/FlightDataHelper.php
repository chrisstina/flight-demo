<?php

namespace backend\components;

use yii\base\Exception; // впоследствииможно создать свой класс эксепшена

/**
 * @author chriss
 */
class FlightDataHelper extends \yii\base\Component {
        
    /**
     * Выполняет поиск по массиву, кидает исключение, если не найден требуемый элемент
     * @throws Exception
     */
    public static function getNode($nodeTitle, $data, $checkArray = true) {
        if ( ! isset($data[$nodeTitle]) || ($checkArray && ! is_array($data[$nodeTitle]))) {
            throw new Exception ('Missing or invalid ' . $nodeTitle . ' field.');
        }
        
        return $data[$nodeTitle];
    }
}

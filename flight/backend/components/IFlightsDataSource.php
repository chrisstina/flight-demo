<?php

namespace backend\components;

/**
 * Источник данных о полете, например, XML-файл
 * 
 * @author chriss
 */
interface IFlightsDataSource {
    public function getData();
}

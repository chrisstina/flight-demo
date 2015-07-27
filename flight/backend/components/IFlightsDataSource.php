<?php

/**
 * Источник данных о полете, например, XML-файл
 * 
 * @author chriss
 */
interface IFlightsDataSource
{
    public function getData();
}

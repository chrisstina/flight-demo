<?php

namespace backend\components;

/**
 * @author chriss
 */
class SearchFlight extends \yii\base\Component {
    
    /* в случае, если изменятся названия полей в источнике данных,
     * можно редактировать их здесь.
     * Этот маппинг можно вынести в настройки или в FlightDataHelper */
    const VAR_FLIGHT_OBJECTS_ROOT = 'ShopOptions';
    const VAR_FLIGHT_OBJECT = 'ShopOption';
    
    /**
     * @return IFlightsDataSource
     */
    public function getDataSource() {
        return new FileFlightsDataSource(); /** имя класса можно будет вынести в настройки */
    }
    
    /**
     * @return IFlightsDataParser
     */
    public function getDataParser() {
        return new XMLFlightsDataParser();
    }
    
    public function sendAndSave() {
        $flightsData = $this->getDataParser()->getFlightsData($this->getDataSource()->getData()); // получили данные в виде массива объектов
        
        foreach ($this->_getFlightsData($flightsData) as $flightData) {
            $flightService = new FlightService($flightData);
            $passengers = $flightService->generatePassengersData();
            
            // сохраняем найденное
            
            $flightModel = new \app\models\Flights();
            $flightModel->back = ! $flightService->isOneWay();
            $flightModel->from = $this->_getAirport($flightService->getDepartureAirport())->id; // здесь либо найденный id, либо id только что созданного
            $flightModel->to = $this->_getAirport($flightService->getArrivalAirport())->id;
            $flightModel->start = $flightService->getDepartureTime();
            $flightModel->stop = $flightService->getArrivalTime();
            $flightModel->price = $flightService->getTotalPrice();
            $flightModel->adult = isset($passengers[FlightService::VAR_FARE_AGE_CATEGORY_TYPE_ADULT]) ? $passengers[FlightService::VAR_FARE_AGE_CATEGORY_TYPE_ADULT] : 0;
            $flightModel->child = isset($passengers[FlightService::VAR_FARE_AGE_CATEGORY_TYPE_CHILD]) ? $passengers[FlightService::VAR_FARE_AGE_CATEGORY_TYPE_CHILD] : 0;
            $flightModel->infant = isset($passengers[FlightService::VAR_FARE_AGE_CATEGORY_TYPE_INFANT]) ? $passengers[FlightService::VAR_FARE_AGE_CATEGORY_TYPE_INFANT] : 0;
            $flightModel->save();
        }
    }
    
    /**
     * Ищет аэропорт по коду, [создает новый, если аэропорт не найден в базе]
     * @return Airports
     */
    private function _getAirport($code) {
        if ( ! $fromAirportModel = Airports::findOne('code = :code', [':code' => $code])) {
            // не нашли - создаем
            /*
            $airport = new \app\models\Airports();
            $airport->code = ...
            $airport->country = ...
            $airport->name = ...
            */
        }
        
        return $fromAirportModel;
    }
    
    /**
     * Получает массив перелетов из источника данных
     * 
     * @param array $flightsData - массив данных, распарсенный из оригинального источнива данных
     * @return array
     * @throws Exception
     */
    private function _getFlightsData($flightsData = array()) {
        FlightDataHelper::getNode(self::VAR_FLIGHT_OBJECTS_ROOT, $flightsData);
        $pureFlightsData = [];
        
        foreach ($flightsData[self::VAR_FLIGHT_OBJECTS_ROOT] as $flightData) {
            $pureFlightsData[] = FlightDataHelper::getNode(self::VAR_FLIGHT_OBJECT, $flightData);
        }
        
        return $pureFlightsData;
    }
}
<?php

namespace backend\components;

/**
 * Класс, описывающий полет, имеет структуру, подходящую для сохранения в модель.
 *
 * @author chriss
 */
class FlightService extends \yii\base\Component {
    
    /* в случае, если изменятся названия полей в источнике данных,
     * можно редактировать их здесь.
     * Этот маппинг можно вынести в настройки или в FlightDataHelper */
    const VAR_ITINERARY_OBJECTS_ROOT = 'ItineraryOptions';
    const VAR_ITINERARY_OBJECT = 'ItineraryOption';
    const VAR_FLIGHT_SEGMENT_OBJECT = 'FlightSegment';
    const VAR_FLIGHT_SEGMENT_OBJECT_DEPARTURE = 'Departure';
    const VAR_FLIGHT_SEGMENT_OBJECT_ARRIVAL = 'Arrival';
    const VAR_FLIGHT_TOTAL_PRICE = 'Total';
    const VAR_FARE_DATA_ROOT = 'FareInfo';
    const VAR_FARES_ROOT = 'Fares';
    const VAR_FARE_OBJECT = 'Fare';
    const VAR_FARE_PAX_TYPE = 'PaxType';
    const VAR_FARE_AGE_CATEGORY = 'AgeCat';
    const VAR_FARE_AGE_CATEGORY_COUNT = 'Count';
    const VAR_FARE_AGE_CATEGORY_TYPE_ADULT = 'ADT';
    const VAR_FARE_AGE_CATEGORY_TYPE_CHILD = 'CLD';
    const VAR_FARE_AGE_CATEGORY_TYPE_INFANT = 'INF';
    
    private $_data; // массив исходных данных
    
    public function __construct($flightData = array()) {
        $this->_data = $flightData;
        parent::__construct();
    }
    
    public function getTravelItinerary() {
        return FlightDataHelper::getNode(0, 
                FlightDataHelper::getNode(self::VAR_ITINERARY_OBJECT, 
                    FlightDataHelper::getNode(self::VAR_ITINERARY_OBJECTS_ROOT, 
                        $this->_data
                    )
                )
            );
    }
    
    public function getTravelBackItinerary() {
        
        try {
            return FlightDataHelper::getNode(0, 
                FlightDataHelper::getNode(self::VAR_ITINERARY_OBJECT, 
                    FlightDataHelper::getNode(self::VAR_ITINERARY_OBJECTS_ROOT, 
                        $this->_data
                    )
                )
            );
        } catch (\yii\base\Exception $e) { // обратного полета может не быть
            return null;
        }
    }
    
    /**
     * Сформирует и вернет массив вида [тип пассажира: количество пассажиров]
     */
    public function generatePassengersData() {
        $allFares = FlightDataHelper::getNode(self::VAR_FARES_ROOT, 
                FlightDataHelper::getNode(self::VAR_FARE_DATA_ROOT, 
                    $this->_data
                )
            );
        
        $faresByPaxType = [];
        
        foreach ($allFares as $fare) {
            $faresByPaxType[$fare[self::VAR_FARE_PAX_TYPE]['@attributes'][self::VAR_FARE_AGE_CATEGORY]] = $fare[self::VAR_FARE_PAX_TYPE]['@attributes'][self::VAR_FARE_AGE_CATEGORY_COUNT];
        }
        
        return $faresByPaxType;
    }

    /**
     * Вернет код аэропорта
     */
    public function getDepartureAirport() {
        $departureItinerary = $this->getTravelItinerary();
        return FlightDataHelper::getNode('From', $departureItinerary['@attributes'], false);
    }
    
    public function getArrivalAirport() {
        $departureItinerary = $this->getTravelItinerary();
        return FlightDataHelper::getNode('To', $departureItinerary['@attributes'], false);
    }
    
    /**
     * @return timestamp
     */
    public function getDepartureTime() {
        $itinerary = $this->getTravelItinerary();
        return strtotime(
            FlightDataHelper::getNode('Time', // Берем значение time из первого элемента flight segment (время первого отправления)
                    $itinerary[self::VAR_FLIGHT_SEGMENT_OBJECT][0][self::VAR_FLIGHT_SEGMENT_OBJECT_DEPARTURE]['@attributes'], 
                    false)
        );
    }
    
    /**
     * Предполагаем, что поле stop - это дата завершения всего путешествия.
     * @return type
     */
    public function getArrivalTime() {
        $itinerary = $this->isOneWay() ? $this->getTravelItinerary() : $this->getTravelBackItinerary();
        return strtotime(
            FlightDataHelper::getNode('Time',  // Берем значение time из последнего элемента flight segment (время последней посадки)
                    $itinerary[self::VAR_FLIGHT_SEGMENT_OBJECT][count($itinerary[self::VAR_FLIGHT_SEGMENT_OBJECT]) - 1][self::VAR_FLIGHT_SEGMENT_OBJECT_ARRIVAL]['@attributes'], 
                    false)
        );
    }
    
    public function isOneWay() {
        FlightDataHelper::getNode(self::VAR_ITINERARY_OBJECTS_ROOT, $this->_data);
        return count($this->_data[self::VAR_ITINERARY_OBJECTS_ROOT]) === 1;
    }
    
    public function getTotalPrice() {
        return FlightDataHelper::getNode(self::VAR_FLIGHT_TOTAL_PRICE, $this->_data['@attributes'], false);
    }
}

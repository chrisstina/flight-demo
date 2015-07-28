<?php

namespace backend\components;

/**
 *
 * @author chriss
 */
interface IFlightsDataParser {
    public function getFlightsData($xmlSource);
}

<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider\HorariosRenfeCom\Parser;

class StationNameParser
{
    private $departureName;

    private $arrivalName;

    public function __construct($html)
    {
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $path = new \DOMXPath($domDocument);
        $this->parse($path);
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);
    }

    protected function parse(\DOMXPath $path)
    {
        $stationNames = $path->query('//*[not(self::a)][@class="titulo_negro"]');
        $departureStationName = "";
        $arrivalStationName = "";
        if ($stationNames->length) {
            $departureStationName = trim($stationNames->item(0)->textContent);
            $arrivalStationName = trim($stationNames->item(1)->textContent);
        }

        $this->departureName = $departureStationName;
        $this->arrivalName = $arrivalStationName;
    }



    public function departureStationName()
    {
        return $this->departureName;
    }

    public function arrivalStationName()
    {
        return $this->arrivalName;
    }
}

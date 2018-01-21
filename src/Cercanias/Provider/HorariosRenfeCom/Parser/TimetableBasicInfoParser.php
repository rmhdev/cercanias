<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider\HorariosRenfeCom\Parser;

class TimetableBasicInfoParser
{
    private $departureName;

    private $arrivalName;

    private $date;

    public function __construct($html)
    {
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "ISO_8859-1");
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

        $this->date = $this->parseDate($path);
    }

    private function parseDate(\DOMXPath $path)
    {
        $dateString = "";
        $firstB = $path->query('//h4/b')->item(0);
        if ($firstB instanceof \DOMNode) {
            $dateString = $firstB->textContent;
        } else {
            $h4 = $path->query('//h4')->item(0)->textContent;
            if (preg_match('/\((.*?)\)/', $h4, $matches)) {
                $dateString = $matches[1];
            }
        }
        $dateString = str_replace(
            array(".", "/", "_", " "),
            array("-", "-", "-", "-"),
            trim($dateString)
        );
        $dateParts = explode("-", $dateString);
        if (3 != sizeof($dateParts)) {
            return $dateString;
        }
        $dateTime = new \DateTime("{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]} 00:00:00");

        return $dateTime->format("Y-m-d");
    }

    /**
     * @return string
     */
    public function departureStationName()
    {
        return $this->departureName;
    }

    /**
     * @return string
     */
    public function arrivalStationName()
    {
        return $this->arrivalName;
    }

    /**
     * @return string
     */
    public function date()
    {
        return $this->date;
    }
}

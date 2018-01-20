<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider\HorariosRenfeCom\Parser;

use Cercanias\Exception\ParseException;

class TimetableTripsParser
{
    const TIMETABLE_WITHOUT_TRANSFER_COLS = 5;
    const TIMETABLE_WITH_TRANSFER_COLS = 8;

    private $transferStationName;
    private $trips;

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

    /**
     * @param \DOMXPath $path
     * @throws ParseException
     */
    protected function parse(\DOMXPath $path)
    {
        $transferStationName = "";
        $allRows = $path->query('//table/tbody/tr');
        if ($allRows->length <= 0) {
            throw new ParseException("Timetable has no rows");
        }

        $headerTds = $allRows->item(0)->getElementsByTagName("td");
        if (self::TIMETABLE_WITHOUT_TRANSFER_COLS == $headerTds->length) {
            $trips = $this->parseNoTransferTrips($allRows);
        } elseif (self::TIMETABLE_WITH_TRANSFER_COLS == $headerTds->length) {
            $transferStationName = $this->parseTransferStationName($allRows->item(1));
            $trips = $this->parseTransferTrips($allRows);
        } else {
            throw new ParseException(sprintf(
                'Number of columns in timetable header is unexpected: "%d"',
                $headerTds->length
            ));
        }



        $this->trips = $trips;
        $this->transferStationName = $transferStationName;
    }

    private function parseNoTransferTrips(\DOMNodeList $rows)
    {
        $trips = array();
        // The first 2 tr are headers and have no trip data.
        for ($i = 1; $i < $rows->length; $i += 1) {
            $trips[] = $this->parseNoTransferTrip(
                //$path->query(".//td", $rows->item($i))
                $rows->item($i)->getElementsByTagName("td")
            );
        }

        return $trips;
    }

    private function parseNoTransferTrip(\DOMNodeList $list)
    {
        return array(
            "line"          => trim($list->item(0)->textContent),
            "description"   => trim($list->item(1)->textContent),
            "departure"     => trim($this->createTime($list->item(2)->textContent)),
            "arrival"       => trim($this->createTime($list->item(3)->textContent)),
            "duration"      => trim($this->createTime($list->item(4)->textContent)),
        );
    }

    private function createTime($string)
    {
        list($hour, $minute) = explode(".", trim($string));

        return sprintf("%s:%s", $hour, $minute);
    }


    public function hasTransfer()
    {
        return strlen($this->transferStationName()) > 0;
    }

    private function parseTransferStationName(\DOMElement $row)
    {
        if ($row->getElementsByTagName("td")->length <= 0) {
            return "";
        }
        return trim($row->getElementsByTagName("td")->item(0)->textContent);
    }

    private function parseTransferTrips(\DOMNodeList $rows)
    {
        return array();
    }

    public function trips()
    {
        return $this->trips;
    }

    public function transferStationName()
    {
        return $this->transferStationName;
    }
}

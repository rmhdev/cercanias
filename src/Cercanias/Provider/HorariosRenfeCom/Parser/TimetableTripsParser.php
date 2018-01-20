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

        // sometimes table has a thead:
        $tripsStartAtRow = 1;
        $thead = $path->query('//table/thead');
        if ($thead && $thead->length > 0) {
            $tripsStartAtRow = 0;
        }

        $headerTds = $allRows->item(0)->getElementsByTagName("td");
        if (self::TIMETABLE_WITHOUT_TRANSFER_COLS == $headerTds->length) {
            $trips = $this->parseNoTransferTrips($allRows, $tripsStartAtRow);
        } elseif (self::TIMETABLE_WITH_TRANSFER_COLS == $headerTds->length) {
            $rowWithTransferName = $allRows->item(1);
            $transferStationName = $this->parseTransferStationName($rowWithTransferName);
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

    private function parseNoTransferTrips(\DOMNodeList $rows, $startFromRow = 1)
    {
        $trips = array();
        for ($i = $startFromRow; $i < $rows->length; $i += 1) {
            $trips[] = $this->parseNoTransferTrip(
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
            "departure"     => $this->createTime($list->item(2)->textContent),
            "arrival"       => $this->createTime($list->item(3)->textContent),
            "duration"      => $this->createDuration($list->item(4)->textContent),
        );
    }

    private function createTime($string)
    {
        return str_replace(".", ":", trim($string));
    }

    private function createDuration($string)
    {
        $result = preg_match_all('/\d+/', $string, $matches);
        if (false === $result) {
            return "";
        }
        if (!$matches) {
            return "";
        }

        return implode(":", $matches[0]);
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
        $trips = array();
        // The first 3 tr are headers and have no trip data.
        for ($i = 3; $i < $rows->length; $i += 1) {
            $trip = $this->parseTransferTrip($rows, $i);
            if ($trip) {
                $trips[] = $trip;
            }
        }

        return $trips;
    }

    private function parseTransferTrip(\DOMNodeList $rows, $i)
    {
        $tds = $rows->item($i)->getElementsByTagName("td");
        $line = trim($tds->item(0)->textContent);
        if (!strlen($line)) {
            return array();
        }
        $values = array(
            "line" => $line,
            "description" => trim($tds->item(1)->textContent),
            "departure"     => $this->createTime($tds->item(2)->textContent),
            "arrival"       => $this->createTime($tds->item(3)->textContent),
            "transfers"     => array(),
        );

        $values["transfers"][] = array(
            "departure"     => trim($this->createTime($tds->item(4)->textContent)),
            "line"          => trim($tds->item(5)->textContent),
            "description"   => trim($tds->item(6)->textContent),
            "arrival"       => $this->createTime($tds->item(7)->textContent),
            "duration"      => $this->createDuration($tds->item(8)->textContent),
        );

        $pos = $i;
        while (true) {
            $pos += 1;
            if (!$rows->item($pos)) {
                break;
            }
            $transferTds = $rows->item($pos)->getElementsByTagName("td");
            if (!$transferTds) {
                break;
            }

            $transferLine = trim($transferTds->item(0)->textContent);
            if (strlen($transferLine) > 0) {
                break;
            }
            $values["transfers"][] = array(
                "departure"     => trim($this->createTime($transferTds->item(4)->textContent)),
                "line"          => trim($transferTds->item(5)->textContent),
                "description"   => trim($transferTds->item(6)->textContent),
                "arrival"       => trim($this->createTime($transferTds->item(7)->textContent)),
                "duration"      => trim($this->createTime($transferTds->item(8)->textContent)),
            );
            if (($pos - $i) >= 15) {
                // just is case...
                break;
            }
        }

        return $values;
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

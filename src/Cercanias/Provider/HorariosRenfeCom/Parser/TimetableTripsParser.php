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
    const TIMETABLE_WITH_MULTI_TRANSFER_COLS = 10;

    private $transferStationNames;
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
        $transferStationNames = array();
        $allRows = $path->query('//table/tbody/tr');
        if ($allRows->length <= 0) {
            throw new ParseException("Timetable has no rows");
        }

        $headerTds = $allRows->item(0)->getElementsByTagName("td");
        // sometimes table has a thead:
        $tripsStartAtRow = 1;
        $tripsWithTransferStartAtRow = 3;
        $theadRows = $path->query('//table/thead/tr');

        if ($theadRows && $theadRows->length > 0) {
            $tripsStartAtRow = 0;
            $tripsWithTransferStartAtRow = 0;
            $headerTds = $theadRows->item(0)->getElementsByTagName("td");
        }

        if (self::TIMETABLE_WITHOUT_TRANSFER_COLS == $headerTds->length) {
            $trips = $this->parseNoTransferTrips($allRows, $tripsStartAtRow);
        } elseif (self::TIMETABLE_WITH_TRANSFER_COLS == $headerTds->length) {
            $rowWithTransferName = ($theadRows && $theadRows->length > 0) ? $theadRows->item(1) : $allRows->item(1);
            $transferStationNames = array($this->parseTransferStationName($rowWithTransferName));
            $trips = $this->parseTransferTrips($allRows, $tripsWithTransferStartAtRow);
        } elseif (self::TIMETABLE_WITH_MULTI_TRANSFER_COLS == $headerTds->length) {
            $tds = $allRows->item(1)->getElementsByTagName("td");
            $totalTransfers = $tds->length;
            for ($i = 0; $i < $totalTransfers; $i += 1) {
                $transferStationNames[] = trim($tds->item($i)->textContent);
            }
            $trips = $this->parseMultiTransferTrips($allRows, $tripsWithTransferStartAtRow);
        } else {
            throw new ParseException(sprintf(
                'Number of columns in timetable header is unexpected: "%d"',
                $headerTds->length
            ));
        }
        $this->trips = $trips;
        $this->transferStationNames = $transferStationNames;
    }

    private function parseTrips(\DOMNodeList $rows, $transferStationNames = array())
    {

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
            "description"   => $this->parseDescription($list->item(1)),
            "departure"     => $this->parseTime($list->item(2)->textContent),
            "arrival"       => $this->parseTime($list->item(3)->textContent),
            "duration"      => $this->parseDuration($list->item(4)->textContent),
        );
    }

    private function parseTime($string)
    {
        return str_replace(".", ":", trim($string));
    }

    private function parseDuration($string)
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

    private function parseDescription(\DOMElement $raw, $append = "")
    {
        $description = trim($raw->textContent);
        $images = $raw->getElementsByTagName("img");
        if ($images->length > 0) {
            $alt = $images->item(0)->attributes->getNamedItem("alt");
            if ($alt) {
                $description = trim($alt->textContent);
            }
        }
        if (!strlen($description)) {
            return $append;
        }
        if (!strlen($append)) {
            return $description;
        }


        return implode(", ", array($description, $append));
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

    private function parseTransferTrips(\DOMNodeList $rows, $startAtRow = 3)
    {
        $trips = array();
        for ($i = $startAtRow; $i < $rows->length; $i += 1) {
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

        // Sometimes there are no transfers and the trip is direct.
        $isDirect = false;
        $arrivalTd = $tds->item(3);
        $extraDescription = "";
        if ($tds->item(3)->attributes) {
            $colspan = $tds->item(3)->attributes->getNamedItem("colspan");
            if ($colspan && $colspan->textContent == 2) {
                $isDirect = true;
                $arrivalTd = $tds->item(6);
                $extraDescription = $this->parseDescription($tds->item(3));
            }
        }

        $values = array(
            "line" => $line,
            "description"   => $this->parseDescription($tds->item(1), $extraDescription),
            "departure"     => $this->parseTime($tds->item(2)->textContent),
            "arrival"       => $this->parseTime($arrivalTd->textContent),
            "transfers"     => array(),
        );

        if ($isDirect) {
            return $values;
        }

        $values["transfers"][] = array(
            "departure"     => trim($this->parseTime($tds->item(4)->textContent)),
            "line"          => trim($tds->item(5)->textContent),
            "description"   => $this->parseDescription($tds->item(6)),
            "arrival"       => $this->parseTime($tds->item(7)->textContent),
            "duration"      => $this->parseDuration($tds->item(8)->textContent),
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
                "departure"     => trim($this->parseTime($transferTds->item(4)->textContent)),
                "line"          => trim($transferTds->item(5)->textContent),
                "description"   => $this->parseDescription($transferTds->item(6)),
                "arrival"       => trim($this->parseTime($transferTds->item(7)->textContent)),
                "duration"      => trim($this->parseTime($transferTds->item(8)->textContent)),
            );
            if (($pos - $i) >= 15) {
                // just is case...
                break;
            }
        }

        return $values;
    }

    private function parseMultiTransferTrips(\DOMNodeList $rows, $startAtRow = 3)
    {
        $trips = array();
        for ($i = $startAtRow; $i < $rows->length; $i += 1) {
            $trip = $this->parseMultiTransferTrip($rows, $i);
            if ($trip) {
                $trips[] = $trip;
            }
        }

        return $trips;
    }

    private function parseMultiTransferTrip(\DOMNodeList $rows, $i)
    {
        return array();
    }

    public function trips()
    {
        return $this->trips;
    }

    /**
     * @param int $transferNumber
     * @return string
     */
    public function transferStationName($transferNumber = 0)
    {
        if (0 == sizeof($this->transferStationNames)) {
            return "";
        }
        if ($transferNumber >= sizeof($this->transferStationNames)) {
            return "";
        }

        return $this->transferStationNames[$transferNumber];
    }

    /**
     * @return int
     */
    public function numTransfers()
    {
        if (!$this->transferStationNames) {
            return 0;
        }

        return sizeof($this->transferStationNames);
    }
}

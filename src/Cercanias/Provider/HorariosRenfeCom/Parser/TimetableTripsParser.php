<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider\HorariosRenfeCom\Parser;

class TimetableTripsParser
{
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

    protected function parse(\DOMXPath $path)
    {
        $trips = array();
        $allRows = $path->query('//table/tbody/tr');
        // The first 2 tr are headers and have no trip data.
        for ($i = 1; $i < $allRows->length; $i += 1) {
            $trips[] = $this->parseTrip(
                $path->query(".//td", $allRows->item($i))
            );
        }

        $this->trips = $trips;
    }

    private function parseTrip(\DOMNodeList $list)
    {
        if ($this->hasTransfers()) {
            return array();
        }

        return $this->parseNoTransferTrip($list);
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



    public function hasTransfers()
    {
        return false;
    }

    public function trips()
    {
        return $this->trips;
    }
}

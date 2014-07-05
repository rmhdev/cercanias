<?php

namespace Cercanias\Provider\HorariosRenfeCom;

use Cercanias\Exception\NotFoundException;
use Cercanias\Exception\ServiceUnavailableException;
use Cercanias\Provider\TimetableParserInterface;
use Cercanias\Provider\AbstractTimetableParser;
use Cercanias\Entity\Train;
use Cercanias\Entity\Trip;

class TimetableParser extends AbstractTimetableParser implements TimetableParserInterface
{
    protected function processHTML($html)
    {
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $path = new \DOMXPath($domDocument);
        $this->checkContent($path);
        $this->parseValues($path);
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);
    }

    protected function checkContent(\DOMXPath $path)
    {
        if ($path->query('//table')->length <= 0) {
            $unavailable = $path->query('//div[@class="lista_cuadradorosa posicion_cuadrado"]');
            if ($unavailable->length) {
                throw new ServiceUnavailableException(trim($unavailable->item(0)->textContent));
            }
            throw new NotFoundException("HTML has no timetable results");
        }
    }

    protected function parseValues(\DOMXPath $path)
    {
        $this->updateDate($path);
        $hasTransfer = $this->isTimetableWithTransfer($path);
        $this->updateStationNames($path, $hasTransfer);
        if ($hasTransfer) {
            $this->updateTimetableWithTransfers($path);
        } else {
            $this->updateTimetableSimple($path);
        }
    }

    protected function updateDate(\DOMXPath $path)
    {
        $date = \DateTime::createFromFormat(
            "d-m-Y",
            $this->retrieveDateString($path)
        );
        $this->setDate($date);
    }

    protected function retrieveDateString(\DOMXPath $path)
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

        return trim($dateString);
    }

    protected function updateStationNames(\DOMXPath $path, $hasTransfer = false)
    {
        $spans = $path->query('//span[@class="titulo_negro"]');
        $departureStationName = "";
        $arrivalStationName = "";
        if ($spans->length) {
            $departureStationName = $spans->item(0)->textContent;
            $arrivalStationName = $spans->item(1)->textContent;
        }
        $this->setDepartureName($departureStationName);
        $this->setDestinationName($arrivalStationName);
        if ($hasTransfer) {
            $this->updateTransferStationName($path);
        }
    }

    protected function isTimetableWithTransfer(\DOMXPath $path)
    {
        $hasTransfer = false;
        $allRows = $path->query('//table/tbody/tr');
        if ($allRows->length) {
            if ($path->query(".//td", $allRows->item(1))->length > 5) {
                $hasTransfer = true;
            }
        }

        return $hasTransfer;
    }

    protected function updateTimetableSimple(\DOMXPath $path)
    {
        $allRows = $path->query('//table/tbody/tr');
        for ($i = 2; $i < $allRows->length; $i += 1) {
            $tds = $path->query(".//td", $allRows->item($i));
            $train = $this->parseDepartureTrain($tds);
            $this->addTrip(new Trip($train));
        }
    }

    protected function parseDepartureTrain(\DOMNodeList $tds)
    {
        $line = $tds->item(0)->textContent;
        $departureTime = $this->createDateTime($tds->item(1)->textContent);
        $this->setFirstDateTimeIfFirst($departureTime);
        $arrivalTime = $this->createDateTime($tds->item(2)->textContent);

        return new Train($line, $departureTime, $arrivalTime);
    }

    protected function updateTimetableWithTransfers(\DOMXPath $path)
    {
        $train = null;
        $transferTrains = array();
        $allRows = $path->query('//table/tbody/tr');
        for ($i = 5; $i < $allRows->length; $i += 1) {
            $tds = $path->query(".//td", $allRows->item($i));
            if ($this->hasLine($tds)) {
                if ($transferTrains && $train) {
                    $this->addTrip(new Trip($train, $transferTrains));
                }
                $train = $this->parseDepartureTrain($tds);
                $transferTrains = array();
            }
            $transferTrains[] = $this->parseTransferTrain($tds);
        }
        if ($transferTrains && $train) {
            $this->addTrip(new Trip($train, $transferTrains));
        }
    }

    protected function hasLine(\DOMNodeList $tds)
    {
        $line = trim($tds->item(0)->textContent);

        return strlen($line) > 0;
    }

    protected function updateTransferStationName(\DOMXPath $path)
    {
        $allRows = $path->query('//table/tbody/tr');
        $tds = $path->query(".//td", $allRows->item(2));
        $this->setTransferName($tds->item(0)->textContent);
    }

    protected function parseTransferTrain(\DOMNodeList $tds)
    {
        $line = $tds->item(4)->textContent;
        $departureTime = $this->createDateTime($tds->item(3)->textContent);
        $arrivalTime = $this->createDateTime($tds->item(5)->textContent);

        return new Train($line, $departureTime, $arrivalTime);
    }

    protected function createDateTime($string)
    {
        $date = clone $this->getDate();
        list($hour, $minute) = explode(".", trim($string));
        $date->setTime((int) $hour, (int) $minute, 0);
        if ($this->isHourInNextDay($hour, $minute)) {
            $date = $date->add(new \DateInterval("P1D"));
        }

        return $date;
    }
}

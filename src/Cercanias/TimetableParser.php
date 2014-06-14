<?php

namespace Cercanias;

class TimetableParser
{

    protected $timetable;
    protected $date;
    /**
     * @var \DateTime
     */
    protected $firstDateTime;

    public function __construct(Timetable $timetable, $html)
    {
        $this->timetable = $timetable;
        $this->date = new \DateTime();
        $this->firstDateTime = null;
        $this->processHTML($html);
    }

    protected function processHTML($html)
    {
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $path = new \DOMXPath($domDocument);
        $this->updateDate($path);
        $this->updateTimetable($path);
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);
    }

    protected function updateDate(\DOMXPath $path)
    {
        $textDate = $this->retrieveDateString($path);
        $this->date = \DateTime::createFromFormat("d-m-Y", $textDate);
        $this->date->setTime(0, 0, 0);
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

    protected function updateTimetable(\DOMXPath $path)
    {
        if ($this->isTimetableWithTransfers($path)) {
            $this->updateTimetableWithTransfers($path);
        } else {
            $this->updateTimetableSimple($path);
        }
    }

    protected function isTimetableWithTransfers(\DOMXPath $path)
    {
        $isSimple = false;
        $allRows = $path->query('//table/tbody/tr');
        if ($allRows->length) {
            if ($path->query(".//td", $allRows->item(1))->length > 5) {
                $isSimple = true;
            }
        }

        return $isSimple;
    }

    protected function updateTimetableSimple(\DOMXPath $path)
    {
        $allRows = $path->query('//table/tbody/tr');
        for ($i = 2; $i < $allRows->length; $i += 1) {
            $tds = $path->query(".//td", $allRows->item($i));
            $train = $this->parseDepartureTrain($tds);
            $this->timetable->addTrip(new Trip($train));
        }
    }

    protected function parseDepartureTrain(\DOMNodeList $tds)
    {
        $line = $tds->item(0)->textContent;
        $departureTime = $this->createDateTime($tds->item(1)->textContent);
        if (!$this->firstDateTime) {
            $this->firstDateTime = clone $departureTime;
        }
        $arrivalTime = $this->createDateTime($tds->item(2)->textContent);

        return new Train($line, $departureTime, $arrivalTime);
    }

    protected function updateTimetableWithTransfers(\DOMXPath $path)
    {
        $allRows = $path->query('//table/tbody/tr');
        for ($i = 5; $i < $allRows->length; $i += 1) {
            $tds = $path->query(".//td", $allRows->item($i));
            $train = $this->parseDepartureTrain($tds);
            $transferTrain = $this->parseTransferTrain($tds);
            $this->timetable->addTrip(new Trip($train, $transferTrain));
        }
    }

    protected function parseTransferTrain(\DOMNodeList $tds)
    {
        $line = $tds->item(4)->textContent;
        $departureTime = $this->createDateTime($tds->item(3)->textContent);
        $arrivalTime = $this->createDateTime($tds->item(5)->textContent);

        return new Train($line, $departureTime, $arrivalTime);
    }

    public function getDate()
    {
        return $this->date;
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

    protected function isHourInNextDay($hour, $minute)
    {
        if (!$this->firstDateTime) {
            return false;
        }
        /* @var \DateTime $testDate */
        $testDate = clone $this->getDate();
        $testDate->setTime($hour, $minute, 0);
        return ($this->firstDateTime > $testDate);
    }

    public function getTimetable()
    {
        return $this->timetable;
    }

}

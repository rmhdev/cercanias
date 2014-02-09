<?php

namespace Cercanias;

class TimetableParser
{

    protected $timetable;
    protected $date;

    public function __construct(Timetable $timetable, $html)
    {
        $this->timetable = $timetable;
        $this->date = new \DateTime();
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
        $firstB = $path->query('//h4/b')->item(0);
        $textDate = trim($firstB->textContent);
        list($day, $month, $year) = explode("-", $textDate);
        $this->date->setDate($year, $month, $day);
        $this->date->setTime(0, 0, 0);
    }

    protected function updateTimetable(\DOMXPath $path)
    {
        $allRows = $path->query('//table/tbody/tr');
        for ($i = 2; $i < $allRows->length; $i += 1) {
            $tds = $path->query(".//td", $allRows->item($i));
            $line = $tds->item(0)->textContent;
            $departureTime = $this->createDateTime($tds->item(1)->textContent);
            $arrivalTime = $this->createDateTime($tds->item(2)->textContent);
            $trip = new Trip($line, $departureTime, $arrivalTime);
            $this->timetable->addTrip($trip);
        }
    }

    public function getDate()
    {
        return $this->date;
    }

    protected function createDateTime($string)
    {
        return new \DateTime();
    }

    public function getTimetable()
    {
        return $this->timetable;
    }

}

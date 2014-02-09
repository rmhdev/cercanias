<?php

namespace Cercanias;

class TimetableParser
{

    protected $timetable;

    public function __construct(Timetable $timetable, $html)
    {
        $this->timetable = $timetable;
        $this->processHTML($html);
    }

    protected function processHTML($html)
    {
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $this->updateTimetable(new \DOMXPath($domDocument));
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);
    }

    public function getDate()
    {
        return new \DateTime("2014-02-10 00:00:00");
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

    protected function createDateTime($string)
    {
        return new \DateTime();
    }

    public function getTimetable()
    {
        return $this->timetable;
    }

}

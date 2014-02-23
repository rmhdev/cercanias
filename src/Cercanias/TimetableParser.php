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
        $date = clone $this->getDate();
        list($hour, $minute) = explode(".", trim($string));
        $date->setTime((int) $hour, (int) $minute, 0);

        return $date;
    }

    public function getTimetable()
    {
        return $this->timetable;
    }

}

<?php

namespace Cercanias\Provider;

use Cercanias\Timetable;

abstract class AbstractTimetableParser implements TimetableParserInterface
{
    protected $query;
    protected $timetable;
    protected $date;
    protected $transferStationName;
    protected $departureStationName;
    protected $arrivalStationName;
    protected $firstDateTime;

    public function __construct(TimetableQuery $query, $html)
    {
        $this->query = $query;
        $this->date = null;
        $this->transferStationName = "";
        $this->departureStationName = "";
        $this->arrivalStationName = "";
        $this->firstDateTime = null;
        $this->processHTML($html);
    }

    abstract protected function processHtml($html);

    public function getDate()
    {
        return $this->date;
    }

    protected function setDate(\DateTime $dateTime)
    {
        $dateTime->setTime(0, 0, 0);
        $this->date = $dateTime;
    }

    /**
     * @return Timetable
     */
    public function getTimetable()
    {
        return $this->timetable;
    }

    protected function setTimetable(Timetable $timetable)
    {
        $this->timetable = $timetable;
    }

    public function getTransferName()
    {
        return $this->transferStationName;
    }

    protected function setTransferStationName($name)
    {
        $this->transferStationName = trim($name);
    }

    public function getDepartureName()
    {
        return $this->departureStationName;
    }

    protected function setDepartureStationName($name)
    {
        $this->departureStationName = trim($name);
    }

    public function getArrivalName()
    {
        return $this->arrivalStationName;
    }

    protected function setArrivalStationName($name)
    {
        $this->arrivalStationName = trim($name);
    }

    protected function getFirstDateTime()
    {
        return $this->firstDateTime;
    }

    protected function setFirstDateTimeIfFirst(\DateTime $dateTime)
    {
        if (!$this->getFirstDateTime()) {
            $this->firstDateTime = clone $dateTime;

            return true;
        }

        return false;
    }

    protected function isHourInNextDay($hour, $minute)
    {
        if (!$this->getFirstDateTime()) {
            return false;
        }
        /* @var \DateTime $testDate */
        $testDate = clone $this->getDate();
        $testDate->setTime($hour, $minute, 0);

        return ($this->getFirstDateTime() > $testDate);
    }
}

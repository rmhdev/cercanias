<?php

namespace Cercanias\Provider;

use Cercanias\Entity\Timetable;

abstract class AbstractTimetableParser implements TimetableParserInterface
{
    private $query;
    private $timetable;
    private $date;
    private $transferName;
    private $departureName;
    private $destinationName;
    private $firstDateTime;

    public function __construct(TimetableQueryInterface $query, $html)
    {
        $this->query = $query;
        $this->date = null;
        $this->transferName = "";
        $this->departureName = "";
        $this->destinationName = "";
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
        return $this->transferName;
    }

    protected function setTransferName($name)
    {
        $this->transferName = trim($name);
    }

    public function getDepartureName()
    {
        return $this->departureName;
    }

    protected function setDepartureName($name)
    {
        $this->departureName = trim($name);
    }

    public function getDestinationName()
    {
        return $this->destinationName;
    }

    protected function setDestinationName($name)
    {
        $this->destinationName = trim($name);
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

    protected function getQuery()
    {
        return $this->query;
    }
}

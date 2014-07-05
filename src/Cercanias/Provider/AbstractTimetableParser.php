<?php

namespace Cercanias\Provider;

use Cercanias\Entity\Trip;

abstract class AbstractTimetableParser
{
    private $date;
    private $trips;
    private $transferName;
    private $departureName;
    private $destinationName;
    private $firstDateTime;

    public function __construct($html)
    {
        $this->date = null;
        $this->trips = new \ArrayIterator();
        $this->transferName = "";
        $this->departureName = "";
        $this->destinationName = "";
        $this->firstDateTime = null;
        $this->processHTML($html);
    }

    abstract protected function processHtml($html);

    /**
     * {@inheritDoc}
     */
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
     * {@inheritDoc}
     */
    public function getTransferName()
    {
        return $this->transferName;
    }

    protected function setTransferName($name)
    {
        $this->transferName = trim($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getDepartureName()
    {
        return $this->departureName;
    }

    protected function setDepartureName($name)
    {
        $this->departureName = trim($name);
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function getTrips()
    {
        return $this->trips;
    }

    protected function addTrip(Trip $trip)
    {
        $this->getTrips()->append($trip);
    }
}

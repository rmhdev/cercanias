<?php

namespace Cercanias;

use Cercanias\Exception\OutOfBoundsException;

class Trip
{
    protected
        $line,
        $departureTime,
        $arrivalTime;

    public function __construct($line, \DateTime $departureTime, \DateTime $arrivalTime = NULL)
    {
        $this->line = strtolower($line);
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
        if ($this->isArrivalTimeOutOfBounds()) {
            throw new OutOfBoundsException();
        }
    }

    protected function isArrivalTimeOutOfBounds()
    {
        return ($this->getArrivalTime()->getTimestamp() < $this->getDepartureTime()->getTimestamp());
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    public function getArrivalTime()
    {
        return is_null($this->arrivalTime) ?
            $this->getDepartureTime() : $this->arrivalTime;
    }

    public function getDuration()
    {
        return $this->getDepartureTime()->diff($this->getArrivalTime());
    }

    public function compareWith(Trip $trip)
    {
        if ($this->isDepartureTimeEqual($trip)) {
            return $this->compareDateTimes($this->getArrivalTime(), $trip->getArrivalTime());
        }

        return $this->compareDateTimes($this->getDepartureTime(), $trip->getDepartureTime());
    }

    protected function isDepartureTimeEqual(Trip $trip)
    {
        return  0 === (
            $trip->getDepartureTime()->getTimestamp() -
            $this->getDepartureTime()->getTimestamp()
        );
    }

    protected function compareDateTimes(\DateTime $first, \DateTime $second)
    {
        if ($first->getTimestamp() === $second->getTimestamp()) {
            return 0;
        }

        return ($first->getTimestamp() - $second->getTimestamp() < 0) ? -1 : 1;
    }

}

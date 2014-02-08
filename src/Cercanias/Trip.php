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
        if (($trip->getDepartureTime()->getTimestamp() - $this->getDepartureTime()->getTimestamp()) >= 0) {
            return 1;
        }

        return -1;
    }
}

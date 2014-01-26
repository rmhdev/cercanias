<?php

namespace Cercanias;

use Cercanias\Exception\OutOfBoundsException;

class Trip
{
    protected
        $departureTime,
        $arrivalTime;

    public function __construct(\DateTime $departureTime, \DateTime $arrivalTime = NULL)
    {
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
}

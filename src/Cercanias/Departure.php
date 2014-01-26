<?php

namespace Cercanias;

class Departure
{
    protected
        $departureTime,
        $arrivalTime;

    public function __construct(\DateTime $departureTime, \DateTime $arrivalTime = NULL)
    {
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
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
        return new \DateInterval("PT0H");
    }
}

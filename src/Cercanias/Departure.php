<?php

namespace Cercanias;

class Departure
{
    protected $departureTime;

    public function __construct(\DateTime $departureTime)
    {
        $this->departureTime = $departureTime;
    }

    public function getDepartureTime()
    {
        return $this->departureTime;
    }
}

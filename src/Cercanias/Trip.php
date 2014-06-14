<?php

namespace Cercanias;

class Trip
{

    protected $departureTrain;

    public function __construct(Train $departureTrain)
    {
        $this->departureTrain = $departureTrain;
    }

    public function getDepartureTrain()
    {
        return $this->departureTrain;
    }

    public function hasTransfer()
    {
        return false;
    }

    public function getDepartureTime()
    {
        return $this->getDepartureTrain()->getDepartureTime();
    }

}

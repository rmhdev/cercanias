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

}

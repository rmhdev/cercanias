<?php

namespace Cercanias;

class Timetable
{
    protected $departure;
    protected $destination;

    public function __construct(Station $departure, Station $destination)
    {
        $this->departure = $departure;
        $this->destination = $destination;
    }

    public function getDeparture()
    {
        return $this->departure;
    }

    public function getDestination()
    {
        return $this->destination;
    }


}
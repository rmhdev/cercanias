<?php

namespace Cercanias;

class Timetable
{
    protected $departure;

    public function __construct(Station $departure)
    {
        $this->departure = $departure;
    }

    public function getDeparture()
    {
        return $this->departure;
    }
}
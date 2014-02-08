<?php

namespace Cercanias;

class Timetable
{
    protected $departure;
    protected $destination;
    protected $trips;

    public function __construct(Station $departure, Station $destination)
    {
        $this->departure = $departure;
        $this->destination = $destination;
        $this->trips = array();
    }

    public function getDeparture()
    {
        return $this->departure;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getTrips()
    {
        return new \ArrayIterator($this->trips);
    }

    public function addTrip(Trip $trip)
    {
        $this->trips[] = $trip;
    }

}

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
        $trips = $this->trips;
        usort($trips, function (Trip $a, Trip $b) {
            return $a->getDepartureTime()->getTimestamp() - $b->getDepartureTime()->getTimestamp();
        });

        return new \ArrayIterator($trips);
    }

    public function addTrip(Trip $trip)
    {
        $this->trips[] = $trip;
    }

}

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
            return $a->compareWith($b);
        });

        return new \ArrayIterator($trips);
    }

    public function addTrip(Trip $trip)
    {
        $this->trips[] = $trip;
    }

    public function nextDeparture(\DateTime $dateTime)
    {
        $nextDepartures = new NextDeparturesFilterIterator($this->getTrips(), $dateTime);
        foreach ($nextDepartures as $nextDeparture) {
            return $nextDeparture;
        }

        return null;
    }

}


class NextDeparturesFilterIterator extends \FilterIterator
{
    protected $dateTime;

    public function __construct($iterator, \DateTime $dateTime)
    {
        parent::__construct($iterator);
        $this->dateTime = $dateTime;
    }

    public function accept()
    {
        /* @var Trip $trip */
        $trip = parent::current();

        return $trip->getDepartureTime()->getTimestamp() > $this->dateTime->getTimestamp();
    }
}

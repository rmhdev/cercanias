<?php

namespace Cercanias;

class Timetable
{
    protected $departure;
    protected $destination;
    protected $trains;

    public function __construct(Station $departure, Station $destination)
    {
        $this->departure = $departure;
        $this->destination = $destination;
        $this->trains = array();
    }

    public function getDeparture()
    {
        return $this->departure;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getTrains()
    {
        $trains = $this->trains;
        usort($trains, function (Train $a, Train $b) {
            return $a->compareWith($b);
        });

        return new \ArrayIterator($trains);
    }

    public function addTrip(Train $trip)
    {
        $this->trains[] = $trip;
    }

    public function nextDeparture(\DateTime $dateTime)
    {
        $nextDepartures = new NextDeparturesFilterIterator($this->getTrains(), $dateTime);
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
        /* @var Train $trip */
        $trip = parent::current();

        return $trip->getDepartureTime()->getTimestamp() > $this->dateTime->getTimestamp();
    }
}

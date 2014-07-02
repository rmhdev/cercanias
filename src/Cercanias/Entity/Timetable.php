<?php

namespace Cercanias\Entity;

use Cercanias\Exception\InvalidArgumentException;

class Timetable
{
    protected $departure;
    protected $destination;
    protected $trips;
    protected $transferName;
    protected $hasTransfer;

    public function __construct(Station $departure, Station $destination, $transfer = null)
    {
        if ($departure->getRouteId() != $destination->getRouteId()) {
            throw new InvalidArgumentException("Stations must have the same RouteId");
        }
        $this->departure = $departure;
        $this->destination = $destination;
        $this->setTransfer($transfer);
        $this->trips = array();
        $this->hasTransfer = false;
    }

    protected function setTransfer($transfer = null)
    {
        if (is_object($transfer)) {
            if (!$transfer instanceof Station) {
                throw new InvalidArgumentException("Unknown type of transfer");
            }
            if ($this->getDeparture()->getRouteId() !== $transfer->getRouteId()) {
                throw new InvalidArgumentException("Transfer station is from different route");
            }
            $transfer = $transfer->getName();
        }
        $this->transferName = $transfer;
    }

    public function getDeparture()
    {
        return $this->departure;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getTransferName()
    {
        return $this->transferName;
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
        $this->updateHasTransfer($trip->hasTransfer());
    }

    /**
     * @param \DateTime $dateTime
     * @param int $limit
     * @return \ArrayIterator
     */
    public function nextTrips(\DateTime $dateTime, $limit = 0)
    {
        $results = new \ArrayIterator();
        $iterator = new NextTripsFilterIterator($this->getTrips(), $dateTime);
        $i = 1;
        foreach ($iterator as $trip) {
            $results->append($trip);
            if ($limit && ($i >= $limit)) {
                break;
            }
            $i += 1;
        }

        return $results;
    }

    public function nextTrip(\DateTime $dateTime)
    {
        $nextDepartures = new NextTripsFilterIterator($this->getTrips(), $dateTime);
        foreach ($nextDepartures as $nextDeparture) {
            return $nextDeparture;
        }

        return null;
    }

    public function hasTransfer()
    {
        return $this->hasTransfer;
    }

    protected function updateHasTransfer($hasTransfer = false)
    {
        if (!$this->hasTransfer()) {
            $this->hasTransfer = $hasTransfer;
        }
    }
}


class NextTripsFilterIterator extends \FilterIterator
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

        return $trip->getDepartureTime() > $this->dateTime;
    }
}

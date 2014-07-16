<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Entity;

use Cercanias\Exception\InvalidArgumentException;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
final class Timetable
{
    private $departure;
    private $destination;
    private $trips;
    private $transferName;
    private $hasTransfer;

    /**
     * @param Station $departure
     * @param Station $destination
     * @param null $transfer
     * @throws \Cercanias\Exception\InvalidArgumentException
     */
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

    /**
     * @return Station
     */
    public function getDeparture()
    {
        return $this->departure;
    }

    /**
     * @return Station
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return string
     */
    public function getTransferName()
    {
        return $this->transferName;
    }

    /**
     * List of trips, ordered by departure time
     * @return \ArrayIterator|Trip[]
     */
    public function getTrips()
    {
        $trips = $this->trips;
        usort($trips, function (Trip $a, Trip $b) {
            return $a->compareWith($b);
        });

        return new \ArrayIterator($trips);
    }

    /**
     * @param Trip $trip
     */
    public function addTrip(Trip $trip)
    {
        $this->trips[] = $trip;
        $this->updateHasTransfer($trip->hasTransfer());
    }

    /**
     * @param \DateTime $dateTime
     * @param int $limit
     * @return \ArrayIterator|Trip[]
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

    /**
     * @param \DateTime $dateTime
     * @return Trip|null
     */
    public function nextTrip(\DateTime $dateTime)
    {
        $nextDepartures = new NextTripsFilterIterator($this->getTrips(), $dateTime);
        foreach ($nextDepartures as $nextDeparture) {
            return $nextDeparture;
        }

        return null;
    }

    /**
     * @return bool
     */
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

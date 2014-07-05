<?php

namespace Cercanias\Entity;

use Cercanias\Exception\OutOfBoundsException;

class Trip
{
    protected $departureTrain;
    protected $transferTrains;

    /**
     * @param Train $departureTrain
     * @param array|null $transferTrains
     */
    public function __construct(Train $departureTrain, $transferTrains = null)
    {
        $this->departureTrain = $departureTrain;
        $this->transferTrains = new \ArrayIterator();
        $this->addTransferTrains($transferTrains);
    }

    protected function addTransferTrains($transferTrains = null)
    {
        if (is_null($transferTrains)) {
            return;
        }
        if ($transferTrains instanceof Train) {
            $transferTrains = array($transferTrains);
        }
        foreach ($transferTrains as $transferTrain) {
            $this->addTransferTrain($transferTrain);
        }
    }

    protected function addTransferTrain(Train $train)
    {
        if ($this->getDepartureTrain()->getArrivalTime() > $train->getDepartureTime()) {
            throw new OutOfBoundsException("Transfer train departs before first train arrives");
        }
        $this->transferTrains->append($train);
    }

    /**
     * @return Train
     */
    public function getDepartureTrain()
    {
        return $this->departureTrain;
    }

    /**
     * @return bool
     */
    public function hasTransfer()
    {
        return $this->getTransferTrains()->count() > 0;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureTime()
    {
        return $this->getDepartureTrain()->getDepartureTime();
    }

    /**
     * @param Trip $trip
     * @return int
     */
    public function compareWith(Trip $trip)
    {
        return $this->getDepartureTrain()->compareWith($trip->getDepartureTrain());
    }

    /**
     * @return \ArrayIterator
     */
    public function getTransferTrains()
    {
        return $this->transferTrains;
    }
}

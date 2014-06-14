<?php

namespace Cercanias;

use Cercanias\Exception\OutOfBoundsException;

class Trip
{

    protected $departureTrain;
    protected $transferTrains;

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

    public function getDepartureTrain()
    {
        return $this->departureTrain;
    }

    public function hasTransfer()
    {
        return $this->getTransferTrains()->count() > 0;
    }

    public function getDepartureTime()
    {
        return $this->getDepartureTrain()->getDepartureTime();
    }

    public function compareWith(Trip $trip)
    {
        return $this->getDepartureTrain()->compareWith($trip->getDepartureTrain());
    }

    public function getTransferTrains()
    {
        return $this->transferTrains;
    }

}

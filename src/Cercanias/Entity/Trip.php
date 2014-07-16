<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Entity;

use Cercanias\Exception\OutOfBoundsException;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
final class Trip
{
    private $departureTrain;
    private $transferTrains;

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
        $this->getTransferTrains()->append($train);
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
     * @return \ArrayIterator|Train[]
     */
    public function getTransferTrains()
    {
        return $this->transferTrains;
    }
}

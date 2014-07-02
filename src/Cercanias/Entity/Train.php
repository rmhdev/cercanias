<?php

namespace Cercanias\Entity;

use Cercanias\Exception\OutOfBoundsException;

class Train
{
    protected $line;
    protected $departureTime;
    protected $arrivalTime;

    public function __construct($line, \DateTime $departureTime, \DateTime $arrivalTime = null)
    {
        $this->setLine($line);
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
        if ($this->isArrivalTimeOutOfBounds()) {
            throw new OutOfBoundsException();
        }
    }

    protected function setLine($line)
    {
        $this->line = strtolower(trim($line));
    }

    protected function isArrivalTimeOutOfBounds()
    {
        return ($this->getArrivalTime() < $this->getDepartureTime());
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    public function getArrivalTime()
    {
        return is_null($this->arrivalTime) ?
            $this->getDepartureTime() : $this->arrivalTime;
    }

    public function getDuration()
    {
        return $this->getDepartureTime()->diff($this->getArrivalTime());
    }

    public function compareWith(Train $train)
    {
        if ($this->isDepartureTimeEqual($train)) {
            return $this->compareDateTimes($this->getArrivalTime(), $train->getArrivalTime());
        }

        return $this->compareDateTimes($this->getDepartureTime(), $train->getDepartureTime());
    }

    protected function isDepartureTimeEqual(Train $train)
    {
        return ($train->getDepartureTime() == $this->getDepartureTime());
    }

    protected function compareDateTimes(\DateTime $first, \DateTime $second)
    {
        if ($first == $second) {
            return 0;
        }

        return ($first < $second) ? -1 : 1;
    }
}

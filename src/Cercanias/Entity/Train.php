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
final class Train
{
    private $line;
    private $departureTime;
    private $arrivalTime;

    /**
     * @param string $line
     * @param \DateTime $departureTime
     * @param \DateTime $arrivalTime
     * @throws \Cercanias\Exception\OutOfBoundsException
     */
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

    /**
     * @return string
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * @return \DateTime
     */
    public function getArrivalTime()
    {
        return is_null($this->arrivalTime) ?
            $this->getDepartureTime() : $this->arrivalTime;
    }

    /**
     * @return bool|\DateInterval
     */
    public function getDuration()
    {
        return $this->getDepartureTime()->diff($this->getArrivalTime());
    }

    /**
     * @param Train $train
     * @return int
     */
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

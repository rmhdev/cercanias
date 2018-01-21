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
    private $linkTrain;

    /**
     * @param string $line
     * @param \DateTime $departureTime
     * @param \DateTime $arrivalTime
     * @param Train|null $linkTrain
     * @throws OutOfBoundsException
     */
    public function __construct($line, \DateTime $departureTime, \DateTime $arrivalTime = null, Train $linkTrain = null)
    {
        $this->setLine($line);
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
        $this->linkTrain = $linkTrain;
        $this->assertArrivalTimeOutOfBounds();
    }

    protected function setLine($line)
    {
        $this->line = strtolower(trim($line));
    }

    /**
     * @throws OutOfBoundsException
     */
    protected function assertArrivalTimeOutOfBounds()
    {
        if ($this->getArrivalTime() < $this->getDepartureTime()) {
            throw new OutOfBoundsException(sprintf(
                'Arrival time "%s" < departure time "%s"',
                $this->getArrivalTime()->format(DATE_ISO8601),
                $this->getDepartureTime()->format(DATE_ISO8601)
            ));
        }
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
     * @return Train|null
     */
    public function getLinkTrain()
    {
        return $this->linkTrain;
    }

    /**
     * @return bool
     */
    public function hasLinkTrain()
    {
        return !is_null($this->getLinkTrain());
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

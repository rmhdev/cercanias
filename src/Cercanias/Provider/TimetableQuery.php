<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider;

use Cercanias\Entity\Route;
use Cercanias\Entity\Station;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
final class TimetableQuery implements TimetableQueryInterface
{
    private $route;
    private $departureStation;
    private $destinationStation;
    private $date;

    public function __construct()
    {
        $this->setDate(new \DateTime("now"));
    }

    /**
     * {@inheritDoc}
     */
    public function setRoute($route)
    {
        if (!$route instanceof Route) {
            $route = new Route($route, "Default");
        }
        $this->route = $route;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteId()
    {
        return $this->route ? $this->route->getId() : null;
    }

    /**
     * {@inheritDoc}
     */
    public function setDeparture($station)
    {
        if (!$station instanceof Station) {
            $station = new Station($station, "Default station", 808);
        }
        $this->departureStation = $station;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDepartureId()
    {
        return $this->departureStation ? $this->departureStation->getId() : null;
    }

    /**
     * {@inheritDoc}
     */
    public function setDestination($station)
    {
        if (!$station instanceof Station) {
            $station = new Station($station, "Default station", 909);
        }
        $this->destinationStation = $station;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDestinationId()
    {
        return $this->destinationStation ? $this->destinationStation->getId() : null;
    }

    /**
     * {@inheritDoc}
     */
    public function setDate(\DateTime $date)
    {
        $this->date = clone $date;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        return (
            $this->getRouteId() &&
            $this->getDepartureId() &&
            $this->getDestinationId()
        );
    }
}

<?php

namespace Cercanias\Provider;

use Cercanias\Route;
use Cercanias\Station;

abstract class AbstractTimetableQuery implements TimetableQueryInterface
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
    public function getDepartureStationId()
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
    public function getDestinationStationId()
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
            $this->getDepartureStationId() &&
            $this->getDestinationStationId()
        );
    }
}

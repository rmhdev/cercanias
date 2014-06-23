<?php

namespace Cercanias\Provider;

use Cercanias\Route;
use Cercanias\Station;

class TimetableQuery
{

    private $route;
    private $departureStation;
    private $destinationStation;
    private $date;

    public function setRoute($route)
    {
        if (!$route instanceof Route) {
            $route = new Route($route, "Default");
        }
        $this->route = $route;

        return $this;
    }

    public function getRouteId()
    {
        return $this->route ? $this->route->getId() : null;
    }

    public function setDeparture($station)
    {
        if (!$station instanceof Station) {
            $station = new Station($station, "Default station", 808);
        }
        $this->departureStation = $station;

        return $this;
    }

    public function getDepartureStationId()
    {
        return $this->departureStation ? $this->departureStation->getId() : null;
    }

    public function setDestination($station)
    {
        if (!$station instanceof Station) {
            $station = new Station($station, "Default station", 909);
        }
        $this->destinationStation = $station;

        return $this;
    }

    public function getDestinationStationId()
    {
        return $this->destinationStation ? $this->destinationStation->getId() : null;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function isValid()
    {
        return (
            $this->getRouteId() &&
            $this->getDepartureStationId() &&
            $this->getDestinationStationId()
        );
    }

}

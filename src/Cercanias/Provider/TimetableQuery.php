<?php

namespace Cercanias\Provider;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Route;

class TimetableQuery
{

    private $route;
    private $departureStationId;
    private $destinationStationId;
    private $date;

    public function __construct()
    {
        $this->route = "";
    }

    public function setRoute($route)
    {
        if (!$route instanceof Route) {
            $route = new Route($route, "Default");
        }
        $this->route = $route;
    }

    public function getRouteId()
    {
        return $this->route ? $this->route->getId() : null;
    }

    public function setDepartureStationId($stationId)
    {
        $this->departureStationId = $stationId;
    }

    public function getDepartureStationId()
    {
        return $this->departureStationId;
    }

    public function setDestinationStationId($stationId)
    {
        $this->destinationStationId = $stationId;
    }

    public function getDestinationStationId()
    {
        return $this->destinationStationId;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

}

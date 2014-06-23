<?php

namespace Cercanias\Provider;

use Cercanias\Route;

class TimetableQuery
{

    private $routeId;
    private $departureStationId;
    private $destinationStationId;
    private $date;

    public function __construct()
    {
        $this->routeId = "";
    }

    public function setRoute($route)
    {
        if ($route instanceof Route) {
            $route = $route->getId();
        }
        $this->routeId = $route;
    }

    public function getRouteId()
    {
        return $this->routeId;
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

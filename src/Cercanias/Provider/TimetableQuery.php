<?php

namespace Cercanias\Provider;

class TimetableQuery
{

    private $routeId;
    private $departureStationId;

    public function __construct()
    {
        $this->routeId = "";
    }

    public function setRouteId($routeId)
    {
        $this->routeId = $routeId;
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

}

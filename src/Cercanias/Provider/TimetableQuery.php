<?php

namespace Cercanias\Provider;

class TimetableQuery
{

    private $routeId;
    private $departureStationId;
    private $destinationStationId;

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

    public function setDestinationStationId($stationId)
    {
        $this->destinationStationId = $stationId;
    }

    public function getDestinationStationId()
    {
        return $this->destinationStationId;
    }

}

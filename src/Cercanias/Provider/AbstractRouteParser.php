<?php

namespace Cercanias\Provider;

use Cercanias\Entity\Station;

abstract class AbstractRouteParser
{
    private $routeId;
    private $routeName;
    private $stations;

    public function __construct($html)
    {
        $this->stations = new \ArrayIterator();
    }

    protected function setRouteId($routeId)
    {
        $this->routeId = $routeId;
    }

    public function getRouteId()
    {
        return $this->routeId;
    }

    protected function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    protected function addStation(Station $station)
    {
        $this->getStations()->append($station);
    }

    /**
     * @return \ArrayIterator
     */
    public function getStations()
    {
        return $this->stations;
    }
}

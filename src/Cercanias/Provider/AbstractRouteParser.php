<?php

namespace Cercanias\Provider;

use Cercanias\Entity\Station;

abstract class AbstractRouteParser implements RouteParserInterface
{
    private $routeId;
    private $routeName;
    private $stations;

    /**
     * @param string $html
     */
    public function __construct($html)
    {
        $this->stations = new \ArrayIterator();
    }

    protected function setRouteId($routeId)
    {
        $this->routeId = $routeId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteId()
    {
        return $this->routeId;
    }

    protected function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    protected function addStation(Station $station)
    {
        $this->getStations()->append($station);
    }

    /**
     * {@inheritDoc}
     */
    public function getStations()
    {
        return $this->stations;
    }
}

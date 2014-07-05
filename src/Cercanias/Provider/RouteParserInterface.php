<?php

namespace Cercanias\Provider;

interface RouteParserInterface
{
    /**
     * @return int
     */
    public function getRouteId();

    /**
     * @return string
     */
    public function getRouteName();

    /**
     * @return \ArrayIterator
     */
    public function getStations();
}

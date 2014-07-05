<?php

namespace Cercanias\Provider;

use Cercanias\Entity\Route;

interface RouteParserInterface
{
    /**
     * Parse html and create a Route object
     * @return Route
     */
    public function getRoute();

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

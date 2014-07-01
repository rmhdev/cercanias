<?php

namespace Cercanias\Provider;

use Cercanias\Route;

interface RouteQueryInterface extends QueryInterface
{
    /**
     * @param Route|int $route
     */
    public function setRoute($route);

    /**
     * @return int
     */
    public function getRouteId();
}

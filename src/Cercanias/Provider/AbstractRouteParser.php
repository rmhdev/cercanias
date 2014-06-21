<?php

namespace Cercanias\Provider;

use Cercanias\Route;

abstract class AbstractRouteParser
{
    protected $route;

    protected function setRoute(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}

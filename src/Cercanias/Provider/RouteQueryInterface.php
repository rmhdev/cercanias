<?php

namespace Cercanias\Provider;

use Cercanias\Route;

interface RouteQueryInterface
{
    /**
     * @param Route|int $route
     */
    public function setRoute($route);

    /**
     * @return int
     */
    public function getRouteId();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return string
     */
    public function generateUrl();
}

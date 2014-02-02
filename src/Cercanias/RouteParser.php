<?php

namespace Cercanias;

class RouteParser
{

    public function __construct($html)
    {

    }

    public function getRoute()
    {
        $route = new Route(61, "San Sebastián");
        for ($i = 1; $i <= 29; $i += 1) {
            $route->addStation(new Station($i, "Default Station {$i}"));
        }
        $route->addStation(new Station(11409, "Default Station 11409"));

        return $route;
    }
}

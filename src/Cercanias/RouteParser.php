<?php

namespace Cercanias;

class RouteParser
{
    public function getRoute()
    {
        $route = new Route(61, "San Sebastián");
        for ($i = 1; $i <= 30; $i += 1) {
            $route->addStation(new Station($i, "Default Station {$i}"));
        }

        return $route;
    }
}

<?php

namespace Cercanias;

use Cercanias\Entity\Route;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteQuery;

class Cercanias
{

    private $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function getRoute($routeId)
    {
        $routeParser = $this->getProvider()->getRouteParser(
            $this->prepareRouteQuery($routeId)
        );
        $route = new Route($routeParser->getRouteId(), $routeParser->getRouteName());
        foreach ($routeParser->getStations() as $station) {
            $route->addStation($station);
        }

        return $route;
    }

    protected function prepareRouteQuery($routeId)
    {
        $query = $routeId;
        if (!$query instanceof RouteQuery) {
            $query = new RouteQuery();
        }
        $query->setRoute($routeId);

        return $query;
    }

    protected function getProvider()
    {
        return $this->provider;
    }
}

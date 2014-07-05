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
        $query = $this->prepareRouteQuery($routeId);

        return new Route(123, "test");
    }

    public function getRouteParser($routeId)
    {
        $query = $this->prepareRouteQuery($routeId);

        return $this->provider->getRouteParser($query);
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
}

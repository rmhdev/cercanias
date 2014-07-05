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
        $route = $routeId;
        if (!$routeId instanceof RouteQuery) {
            $route = new RouteQuery();
        }
        $route->setRoute($routeId);

        return new Route(123, "test");
    }
}

<?php

namespace Cercanias\Provider;

class TimetableQuery
{

    private $routeId;

    public function __construct()
    {
        $this->routeId = "";
    }

    public function setRouteId($routeId)
    {
        $this->routeId = $routeId;
    }

    public function getRouteId()
    {
        return $this->routeId;
    }

}

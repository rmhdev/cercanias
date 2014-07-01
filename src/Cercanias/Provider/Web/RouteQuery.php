<?php

namespace Cercanias\Provider\Web;

use Cercanias\Route;

class RouteQuery
{

    const BASE_URL = "http://horarios.renfe.com/cer/hjcer300.jsp";

    private $route;

    public function __construct()
    {
        $this->route = null;
    }

    public function setRoute($route)
    {
        if (!$route instanceof Route) {
            $route = new Route($route, "Unknow route");
        }
        $this->route = $route;
    }

    public function getRouteId()
    {
        return $this->getRoute()->getId();
    }

    /**
     * @return Route
     */
    protected function getRoute()
    {
        return $this->route;
    }

    public function isValid()
    {
        return ($this->getRoute() instanceof Route);
    }

    public function generateUrl()
    {
        $params = array();
        foreach ($this->prepareUrlParameters() as $name => $value) {
            $params[] = sprintf("%s=%s", $name, $value);
        }

        return self::BASE_URL . "?" . implode("&", $params);
    }

    private function prepareUrlParameters()
    {
        return array(
            "NUCLEO"    => $this->getRouteId(),
            "CP"        => "NO",
            "I"         => "s"
        );
    }
}

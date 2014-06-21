<?php

namespace Cercanias\Provider;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\HttpAdapter\HttpAdapterInterface;
use Cercanias\RouteParser;

class WebProvider
{
    const ROUTE_URL = "http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=%s&CP=NO&I=s";

    protected $httpAdapter;

    public function __construct(HttpAdapterInterface $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
    }

    public function getName()
    {
        return 'web_provider';
    }

    public function getRoute($id)
    {
        $query = $this->buildQuery(array("route_id" => $id));
        if (is_null($id) || !is_int($id)) {
            throw new InvalidArgumentException(sprintf("Could not execute query %s", $query));
        }
        $routeParser = new RouteParser($this->httpAdapter->getContent($query));

        return $routeParser->getRoute();
    }

    protected function buildQuery($parameters = array())
    {
        if (!isset($parameters["route_id"])) {
            $parameters["route_id"] = "";
        }

        return sprintf(self::ROUTE_URL, $parameters["route_id"]);
    }
}

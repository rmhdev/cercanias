<?php

namespace Cercanias\Provider;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\HttpAdapter\HttpAdapterInterface;
use Cercanias\RouteParser;

class WebProvider
{
    const URL_ROUTE = "http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=%s&CP=NO&I=s";

    const ROUTE_ASTURIAS = 20;
    const ROUTE_BARCELONA = 50;
    const ROUTE_BILBAO = 60;
    const ROUTE_CADIZ = 31;
    const ROUTE_MADRID = 10;
    const ROUTE_MALAGA = 32;
    const ROUTE_MURCIA_ALICANTE = 41;
    const ROUTE_SAN_SEBASTIAN = 61;
    const ROUTE_SANTANDER = 62;
    const ROUTE_SEVILLA = 30;
    const ROUTE_VALENCIA = 40;
    const ROUTE_ZARAGOZA = 70;

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

        return sprintf(self::URL_ROUTE, $parameters["route_id"]);
    }
}

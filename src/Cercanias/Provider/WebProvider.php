<?php

namespace Cercanias\Provider;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\HttpAdapter\HttpAdapterInterface;
use Cercanias\RouteParser;
use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\TimetableParser;

class WebProvider
{
    const URL_ROUTE = "http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=%s&CP=NO&I=s";
    const URL_TIMETABLE = "http://horarios.renfe.com/cer/hjcer310.jsp?nucleo=%s&i=s&cp=NO&o=%s&d=%s&df=%s&ho=00&hd=26&TXTInfo=";

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
        $query = $this->buildRouteQuery(array("route_id" => $id));
        if (is_null($id) || !is_int($id)) {
            throw new InvalidArgumentException(sprintf("Could not execute query %s", $query));
        }
        $routeParser = new RouteParser($this->httpAdapter->getContent($query));

        return $routeParser->getRoute();
    }

    protected function buildRouteQuery($parameters = array())
    {
        if (!isset($parameters["route_id"])) {
            $parameters["route_id"] = "";
        }

        return sprintf(self::URL_ROUTE, $parameters["route_id"]);
    }

    public function getTimetable(Station $from, Station $to, \DateTime $dateTime)
    {
        $query = $this->buildTimetableQuery(array(
            "date" => $this->formatDate($dateTime),
            "from_station_id" => $from->getId(),
            "to_station_id" => $to->getId(),
            "route_id" => $from->getRouteId()
        ));
        $timetable = new Timetable($from, $to);
        $parser = new TimetableParser($timetable, $this->httpAdapter->getContent($query));

        return $parser->getTimetable();
    }

    protected function buildTimetableQuery($parameters = array())
    {
        if (!isset($parameters["date"]) || (!$parameters["date"])) {
            $parameters["date"] = $this->formatDate();
        }

        return sprintf(self::URL_TIMETABLE,
            $parameters["date"],
            $parameters["from_station_id"],
            $parameters["to_station_id"],
            $parameters["route_id"]
        );
    }

    protected function formatDate(\DateTime $date = NULL)
    {
        if (is_null($date)) {
            $date = new \DateTime("now");
        }
        return $date->format("Ymd");
    }
}

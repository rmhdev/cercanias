<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\TimetableQuery;
use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\Provider\AbstractProvider;
use Cercanias\Provider\ProviderInterface;

class Provider extends AbstractProvider implements ProviderInterface
{

    const URL_ROUTE = "http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=%s&CP=NO&I=s";
    const URL_TIMETABLE = "http://horarios.renfe.com/cer/hjcer310.jsp?nucleo=%s&i=s&cp=NO&o=%s&d=%s&df=%s&ho=00&hd=26&TXTInfo=";

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'web_provider';
    }

    /**
     * {@inheritDoc}
     */
    public function getRoute($id)
    {
        $query = $this->buildRouteQuery(array("route_id" => $id));
        if (is_null($id) || !is_int($id)) {
            throw new InvalidArgumentException(sprintf("Could not execute query %s", $query));
        }
        $routeParser = new RouteParser($this->getHttpAdapter()->getContent($query));

        return $routeParser->getRoute();
    }

    protected function buildRouteQuery($parameters = array())
    {
        if (!isset($parameters["route_id"])) {
            $parameters["route_id"] = "";
        }

        return sprintf(self::URL_ROUTE, $parameters["route_id"]);
    }

    /**
     * {@inheritDoc}
     */
    public function getTimetable(TimetableQuery $query)
    {
//        $query = $this->buildTimetableQuery(array(
//            "date" => $this->formatDate($dateTime),
//            "from_station_id" => $from->getId(),
//            "to_station_id" => $to->getId(),
//            "route_id" => $from->getRouteId()
//        ));
//        $timetable = new Timetable($from, $to);
        $parser = new TimetableParser($query, $this->getHttpAdapter()->getContent($query));

        return $parser->getTimetable();
    }

    protected function buildTimetableQuery($parameters = array())
    {
        if (!isset($parameters["date"]) || (!$parameters["date"])) {
            $parameters["date"] = $this->formatDate();
        }

        return sprintf(self::URL_TIMETABLE,
            $parameters["route_id"],
            $parameters["from_station_id"],
            $parameters["to_station_id"],
            $parameters["date"]
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

<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\TimetableQuery;
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
        $url = $this->buildRouteUrl(array("route_id" => $id));
        if (is_null($id) || !is_int($id)) {
            throw new InvalidArgumentException(sprintf("Could not execute query %s", $url));
        }
        $routeParser = new RouteParser($this->getHttpAdapter()->getContent($url));

        return $routeParser->getRoute();
    }

    protected function buildRouteUrl($parameters = array())
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
        if (!$query->isValid()) {
            throw new InvalidArgumentException("not valid");
        }
        $url = $this->buildTimetableUrl($query);
        $parser = new TimetableParser($query, $this->getHttpAdapter()->getContent($url));

        return $parser->getTimetable();
    }

    protected function buildTimetableUrl(TimetableQuery $query)
    {
        return sprintf(self::URL_TIMETABLE,
            $query->getRouteId(),
            $query->getDepartureStationId(),
            $query->getDestinationStationId(),
            $query->getDate()->format("Ymd")
        );
    }

}

<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\AbstractProvider;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\TimetableQueryInterface;

class Provider extends AbstractProvider implements ProviderInterface
{
    const HOST = "http://horarios.renfe.com";
    const URL_ROUTE = "/cer/hjcer300.jsp?NUCLEO=%s&CP=NO&I=s";
    const URL_TIMETABLE = "/cer/hjcer310.jsp?nucleo=%s&i=s&cp=NO&o=%s&d=%s&df=%s&ho=00&hd=26&TXTInfo=";

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

        return sprintf($this->getUrlRoute(), $parameters["route_id"]);
    }

    protected function getUrlRoute()
    {
        return self::HOST . self::URL_ROUTE;
    }

    /**
     * {@inheritDoc}
     */
    public function getTimetable(TimetableQueryInterface $query)
    {
        if (!$query->isValid()) {
            throw new InvalidArgumentException("TimetableQuery is not valid");
        }
        $url = $this->buildTimetableUrl($query);
        $parser = new TimetableParser($query, $this->getHttpAdapter()->getContent($url));

        return $parser->getTimetable();
    }

    protected function buildTimetableUrl(TimetableQueryInterface $query)
    {
        return sprintf(
            $this->getUrlTimetable(),
            $query->getRouteId(),
            $query->getDepartureStationId(),
            $query->getDestinationStationId(),
            $query->getDate()->format("Ymd")
        );
    }

    protected function getUrlTimetable()
    {
        return self::HOST . self::URL_TIMETABLE;
    }
}

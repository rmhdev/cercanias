<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\AbstractProvider;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Provider\RouteQuery;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'web_provider';
    }

//    /**
//     * {@inheritDoc}
//     */
//    public function getRoute($routeId)
//    {
//        $query = $this->prepareRouteQuery($routeId);
//        if (!$query->isValid()) {
//            throw new InvalidArgumentException("RouteQuery is not valid");
//        }
//        $routeParser = new RouteParser(
//            $this->getHttpAdapter()->getContent($this->generateRouteUrl($query))
//        );
//
//        return $routeParser->getRoute();
//    }
//
//    private function prepareRouteQuery($routeId)
//    {
//        $query = $routeId;
//        if (!$query instanceof RouteQuery) {
//            $query = new RouteQuery();
//            $query->setRoute($routeId);
//        }
//
//        return $query;
//    }

    /**
     * {@inheritDoc}
     */
    public function getTimetable(TimetableQueryInterface $query)
    {
        if (!$query->isValid()) {
            throw new InvalidArgumentException("TimetableQuery is not valid");
        }
        $parser = new TimetableParser(
            $query,
            $this->getHttpAdapter()->getContent(
                $this->generateTimetableUrl($query)
            )
        );

        return $parser->getTimetable();
    }

    /**
     * {@inheritDoc}
     */
    public function generateRouteUrl(RouteQueryInterface $query)
    {
        return $this->generateUrl($this->getBaseRouteUrl(), $this->getRouteUrlParameters($query));
    }

    protected function generateUrl($baseUrl, $parameters = array())
    {
        $params = array();
        foreach ($parameters as $name => $value) {
            $params[] = sprintf("%s=%s", $name, $value);
        }

        return $baseUrl . "?" . implode("&", $params);
    }

    protected function getBaseRouteUrl()
    {
        return "http://horarios.renfe.com/cer/hjcer300.jsp";
    }

    protected function getRouteUrlParameters(RouteQueryInterface $query)
    {
        return array(
            "NUCLEO"    => $query->getRouteId(),
            "CP"        => "NO",
            "I"         => "s"
        );
    }

    /**
     * {@inheritDoc}
     */
    public function generateTimetableUrl(TimetableQueryInterface $query)
    {
        return $this->generateUrl($this->getBaseTimetableUrl(), $this->getTimetableUrlParameters($query));
    }

    protected function getBaseTimetableUrl()
    {
        return "http://horarios.renfe.com/cer/hjcer310.jsp";
    }

    protected function getTimetableUrlParameters(TimetableQueryInterface $query)
    {
        return array(
            "nucleo"    => $query->getRouteId(),
            "i"         => "s",
            "cp"        => "NO",
            "o"         => $query->getDepartureStationId(),
            "d"         => $query->getDestinationStationId(),
            "df"        => $query->getDate()->format("Ymd"),
            "ho"        => "00",
            "hd"        => "26",
            "TXTInfo"   => ""
        );
    }

    public function getRouteParser(RouteQueryInterface $query)
    {
        if (!$query->isValid()) {
            throw new InvalidArgumentException("RouteQuery is not valid");
        }

        return new RouteParser(
            $this->getHttpAdapter()->getContent(
                $this->generateRouteUrl($query)
            )
        );
    }
}

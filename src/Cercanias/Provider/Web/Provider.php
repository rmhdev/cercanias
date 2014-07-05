<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\AbstractProvider;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;

class Provider extends AbstractProvider implements ProviderInterface
{
    const URL_ROUTE     = "http://horarios.renfe.com/cer/hjcer300.jsp";
    const URL_TIMETABLE = "http://horarios.renfe.com/cer/hjcer310.jsp";

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
    public function generateRouteUrl(RouteQueryInterface $query)
    {
        return $this->generateUrl(self::URL_ROUTE, $this->getRouteUrlParameters($query));
    }

    protected function generateUrl($baseUrl, $parameters = array())
    {
        $params = array();
        foreach ($parameters as $name => $value) {
            $params[] = sprintf("%s=%s", $name, $value);
        }

        return $baseUrl . "?" . implode("&", $params);
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
        return $this->generateUrl(self::URL_TIMETABLE, $this->getTimetableUrlParameters($query));
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

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
    public function getTimetableParser(TimetableQueryInterface $query)
    {
        if (!$query->isValid()) {
            throw new InvalidArgumentException("TimetableQuery is not valid");
        }

        return new TimetableParser($this->getHttpAdapter()->getContent($this->generateTimetableUrl($query)));
    }
}

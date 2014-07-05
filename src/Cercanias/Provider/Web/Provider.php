<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\AbstractProvider;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;

class Provider extends AbstractProvider implements ProviderInterface
{
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
    public function getRoute($routeId)
    {
        $query = $this->prepareRouteQuery($routeId);
        if (!$query->isValid()) {
            throw new InvalidArgumentException("RouteQuery is not valid");
        }
        $routeParser = new RouteParser(
            $this->getHttpAdapter()->getContent($query->generateUrl())
        );

        return $routeParser->getRoute();
    }

    private function prepareRouteQuery($routeId)
    {
        $query = $routeId;
        if (!$query instanceof RouteQuery) {
            $query = new RouteQuery();
            $query->setRoute($routeId);
        }

        return $query;
    }

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
                $query->generateUrl()
            )
        );

        return $parser->getTimetable();
    }

    public function generateRouteUrl(RouteQueryInterface $query)
    {
        return $this->generateUrl($this->getBaseUrl(), $this->getUrlParameters($query));
    }

    protected function generateUrl($baseUrl, $parameters = array())
    {
        $params = array();
        foreach ($parameters as $name => $value) {
            $params[] = sprintf("%s=%s", $name, $value);
        }

        return $baseUrl . "?" . implode("&", $params);
    }

    protected function getBaseUrl()
    {
        return "http://horarios.renfe.com/cer/hjcer300.jsp";
    }

    protected function getUrlParameters(RouteQueryInterface $query)
    {
        return array(
            "NUCLEO"    => $query->getRouteId(),
            "CP"        => "NO",
            "I"         => "s"
        );
    }
}

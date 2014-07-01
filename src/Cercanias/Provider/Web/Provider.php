<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\AbstractProvider;
use Cercanias\Provider\ProviderInterface;
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
}

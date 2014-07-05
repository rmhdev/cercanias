<?php

namespace Cercanias;

use Cercanias\Entity\Route;
use Cercanias\Entity\Station;
use Cercanias\Entity\Timetable;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteQuery;
use Cercanias\Provider\TimetableQueryInterface;

final class Cercanias implements CercaniasInterface
{
    private $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoute($routeId)
    {
        $routeParser = $this->getProvider()->getRouteParser(
            $this->prepareRouteQuery($routeId)
        );
        $route = new Route($routeParser->getRouteId(), $routeParser->getRouteName());
        foreach ($routeParser->getStations() as $station) {
            $route->addStation($station);
        }

        return $route;
    }

    protected function prepareRouteQuery($routeId)
    {
        $query = $routeId;
        if (!$query instanceof RouteQuery) {
            $query = new RouteQuery();
        }
        $query->setRoute($routeId);

        return $query;
    }

    protected function getProvider()
    {
        return $this->provider;
    }

    /**
     * {@inheritDoc}
     */
    public function getTimetable(TimetableQueryInterface $query)
    {
        $parser = $this->getProvider()->getTimetableParser($query);
        $timetable = new Timetable(
            new Station(
                $query->getDepartureStationId(),
                $parser->getDepartureName(),
                $query->getRouteId()
            ),
            new Station(
                $query->getDestinationStationId(),
                $parser->getDestinationName(),
                $query->getRouteId()
            ),
            $parser->getTransferName()
        );
        foreach ($parser->getTrips() as $trip) {
            $timetable->addTrip($trip);
        }

        return $timetable;
    }
}

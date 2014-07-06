<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias;

use Cercanias\Entity\Route;
use Cercanias\Entity\Station;
use Cercanias\Entity\Timetable;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteQuery;
use Cercanias\Provider\TimetableQueryInterface;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
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
        $query  = $this->prepareRouteQuery($routeId);
        $parser = $this->getProvider()->getRouteParser($query);
        $route  = new Route($parser->getRouteId(), $parser->getRouteName());
        foreach ($parser->getStations() as $station) {
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
                $query->getDepartureId(),
                $parser->getDepartureName(),
                $query->getRouteId()
            ),
            new Station(
                $query->getDestinationId(),
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

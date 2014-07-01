<?php

namespace Cercanias\Provider;

use Cercanias\Route;
use Cercanias\Station;

interface TimetableQueryInterface
{
    /**
     * @param Route|int $route
     * @return self
     */
    public function setRoute($route);

    /**
     * @return int
     */
    public function getRouteId();

    /**
     * @param Station|string $station
     * @return self
     */
    public function setDeparture($station);

    /**
     * @return string
     */
    public function getDepartureStationId();

    /**
     * @param Station|string $station
     * @return self
     */
    public function setDestination($station);

    /**
     * @return string
     */
    public function getDestinationStationId();

    /**
     * @param \DateTime $date
     * @return self
     */
    public function setDate(\DateTime $date);

    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return string
     */
    public function generateUrl();
}

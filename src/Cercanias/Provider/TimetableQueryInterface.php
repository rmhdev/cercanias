<?php

namespace Cercanias\Provider;

use Cercanias\Entity\Route;
use Cercanias\Entity\Station;

interface TimetableQueryInterface extends QueryInterface
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
    public function getDepartureId();

    /**
     * @param Station|string $station
     * @return self
     */
    public function setDestination($station);

    /**
     * @return string
     */
    public function getDestinationId();

    /**
     * @param \DateTime $date
     * @return self
     */
    public function setDate(\DateTime $date);

    /**
     * @return \DateTime
     */
    public function getDate();
}

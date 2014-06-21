<?php

namespace Cercanias\Provider;

use Cercanias\Station;
use Cercanias\Route;
use Cercanias\Timetable;

interface ProviderInterface
{

    /**
     * Get the name of the provider
     * @return string
     */
    public function getName();

    /**
     * Retrieve a Route object
     * @param $routeId
     * @return Route
     */
    public function getRoute($routeId);

    /**
     * Retrieve a Timetable Object
     * @param Station $from
     * @param Station $to
     * @param \DateTime $date
     * @return Timetable
     */
    public function getTimetable(Station $from, Station $to, \DateTime $date);
}
<?php

namespace Cercanias\Provider;

use Cercanias\Station;
use Cercanias\Route;
use Cercanias\Timetable;

interface ProviderInterface
{

    const ROUTE_ASTURIAS = 20;
    const ROUTE_BARCELONA = 50;
    const ROUTE_BILBAO = 60;
    const ROUTE_CADIZ = 31;
    const ROUTE_MADRID = 10;
    const ROUTE_MALAGA = 32;
    const ROUTE_MURCIA_ALICANTE = 41;
    const ROUTE_SAN_SEBASTIAN = 61;
    const ROUTE_SANTANDER = 62;
    const ROUTE_SEVILLA = 30;
    const ROUTE_VALENCIA = 40;
    const ROUTE_ZARAGOZA = 70;

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
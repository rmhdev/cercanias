<?php

namespace Cercanias;

use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Entity\Route;
use Cercanias\Entity\Timetable;

interface CercaniasInterface
{
    /**
     * Retrieve a Route
     * @param int|RouteQueryInterface $routeId
     * @return Route
     */
    public function getRoute($routeId);

    /**
     * Retrieve a Timetable
     * @param TimetableQueryInterface $query
     * @return Timetable
     */
    public function getTimetable(TimetableQueryInterface $query);
}

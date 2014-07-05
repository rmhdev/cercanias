<?php

namespace Cercanias;

use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Entity\Route;
use Cercanias\Entity\Timetable;

interface CercaniasInterface
{
    /**
     * Generate a Route object
     * @param int|RouteQueryInterface $routeId
     * @return Route
     */
    public function getRoute($routeId);

    /**
     * Generate a Timetable object
     * @param TimetableQueryInterface $query
     * @return Timetable
     */
    public function getTimetable(TimetableQueryInterface $query);
}

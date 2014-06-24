<?php

namespace Cercanias\Provider;

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
     * @param TimetableQuery $query
     * @return Timetable
     */
    public function getTimetable(TimetableQuery $query);
}
<?php

namespace Cercanias\Provider;

use Cercanias\Entity\Timetable;

interface ProviderInterface
{

    /**
     * Get the name of the provider
     * @return string
     */
    public function getName();

    /**
     * Retrieve a Timetable Object
     * @param TimetableQueryInterface $query
     * @return Timetable
     */
    public function getTimetable(TimetableQueryInterface $query);


    /**
     * Create a URL for a route
     * @param RouteQueryInterface $query
     * @return string
     */
    public function generateRouteUrl(RouteQueryInterface $query);

    /**
     * Create a URL for a route
     * @param TimetableQueryInterface $query
     * @return string
     */
    public function generateTimetableUrl(TimetableQueryInterface $query);

    /**
     * @param RouteQueryInterface $query
     * @return RouteParserInterface
     */
    public function getRouteParser(RouteQueryInterface $query);
}

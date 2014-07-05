<?php

namespace Cercanias\Provider;

interface ProviderInterface
{

    /**
     * Get the name of the provider
     * @return string
     */
    public function getName();

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

    /**
     * Retrieve a Timetable Object
     * @param TimetableQueryInterface $query
     * @return TimetableParserInterface
     */
    public function getTimetableParser(TimetableQueryInterface $query);
}

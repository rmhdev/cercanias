<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
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

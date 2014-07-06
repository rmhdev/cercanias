<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias;

use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Entity\Route;
use Cercanias\Entity\Timetable;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
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

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
interface RouteParserInterface
{
    /**
     * @return int
     */
    public function getRouteId();

    /**
     * @return string
     */
    public function getRouteName();

    /**
     * @return \ArrayIterator
     */
    public function getStations();
}

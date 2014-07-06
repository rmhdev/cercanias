<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider;

use Cercanias\Entity\Station;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
abstract class AbstractRouteParser implements RouteParserInterface
{
    private $routeId;
    private $routeName;
    private $stations;

    /**
     * @param string $html
     */
    public function __construct($html)
    {
        $this->stations = new \ArrayIterator();
    }

    protected function setRouteId($routeId)
    {
        $this->routeId = $routeId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteId()
    {
        return $this->routeId;
    }

    protected function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    protected function addStation(Station $station)
    {
        $this->getStations()->append($station);
    }

    /**
     * {@inheritDoc}
     */
    public function getStations()
    {
        return $this->stations;
    }
}

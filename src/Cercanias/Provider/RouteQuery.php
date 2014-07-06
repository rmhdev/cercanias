<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider;

use Cercanias\Entity\Route;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
final class RouteQuery implements RouteQueryInterface
{
    private $route;

    public function __construct()
    {
        $this->route = null;
    }

    /**
     * {@inheritDoc}
     */
    public function setRoute($route)
    {
        if (!$route instanceof Route) {
            $route = new Route($route, "Unknown route");
        }
        $this->route = $route;
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteId()
    {
        return $this->getRoute()->getId();
    }

    /**
     * @return Route
     */
    protected function getRoute()
    {
        return $this->route;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        return ($this->getRoute() instanceof Route);
    }
}

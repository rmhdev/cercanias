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
interface RouteQueryInterface extends QueryInterface
{
    /**
     * @param Route|string $route
     */
    public function setRoute($route);

    /**
     * @return string
     */
    public function getRouteId();
}

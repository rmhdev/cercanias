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
use Cercanias\Entity\Station;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
interface TimetableQueryInterface extends QueryInterface
{
    /**
     * @param Route|string $route
     * @return self
     */
    public function setRoute($route);

    /**
     * @return string
     */
    public function getRouteId();

    /**
     * @param Station|string $station
     * @return self
     */
    public function setDeparture($station);

    /**
     * @return string
     */
    public function getDepartureId();

    /**
     * @param Station|string $station
     * @return self
     */
    public function setDestination($station);

    /**
     * @return string
     */
    public function getDestinationId();

    /**
     * @param \DateTime $date
     * @return self
     */
    public function setDate(\DateTime $date);

    /**
     * @return \DateTime
     */
    public function getDate();
}
